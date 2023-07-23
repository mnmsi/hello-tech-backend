<?php

namespace Modules\Api\Http\Traits\Order;

use App\Models\Order\Cart;
use App\Models\Order\Order;
use App\Models\OrderDetails;
use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use App\Models\ProductData;
use App\Models\ProductFeatureValue;
use App\Models\System\DeliveryOption;
use App\Models\System\PaymentMethod;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Traits\Payment\PaymentTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;

trait
OrderTrait
{
    use productTrait;

    public function getDeliveryOptions()
    {
        return DeliveryOption::where('is_active', 1)
            ->select('id', 'name', 'amount')
            ->get();
    }

    public function getPaymentMethods()
    {
        return PaymentMethod::where('is_active', 1)
            ->select('id', 'name')
            ->get();
    }

    public function storeOrder($data)
    {
        DB::beginTransaction();
        try {
            $total_discountRate = 0;
            $subtotal_price = 0;
            $carts = Cart::select('id', 'product_id', 'product_color_id', 'product_data', 'quantity')
                ->where('user_id', Auth::id())->get();

            foreach ($carts as $c) {
                $product = Product::find($c['product_id']);
                $product->stock = $product->stock - $c['quantity'];
                $product->save();
//                price calculation
                if ($product->discount_rate) {
                    if ($product->discount_rate == 100) {
                        $pp = 0;
                        $total_discountRate += 100;
                    } else {
                        $pp = $product->price - (($product->price * $product->discount_rate) / 100);
                        $total_discountRate += $product->discount_rate;
                    }
                    $subtotal_price = $pp * $c['quantity'];
                } else {
                    $subtotal_price = $product->price * $c['quantity'];
                }
//                product color price
                $product_color = ProductColor::find($c['product_color_id']);
                if ($product_color) {
                    $subtotal_price += $product_color->price * $c['quantity'];
                    $product_color->stock = $product_color->stock - $c['quantity'];
                    $product_color->save();
                }

//                product feature
                if (!empty($c['product_data'])) {
                    $product_feature = ProductFeatureValue::whereIn('id', json_decode($c['product_data']))->get();
                    if ($product_feature) {
                        $total_feature = $product_feature->sum('price');
                        $subtotal_price += $total_feature * $c['quantity'];

                        foreach ($product_feature as $f) {
                            $ff = ProductFeatureValue::find($f['id']);
                            $ff->stock = $f['stock'] - $c['quantity'];
                            $ff->save();
                        }
                    }
                }
            }

//            $products = Product::whereIn('id', $carts->pluck('product_id'))->get();
//            $total_discountRate = $products->sum('discount_rate');
//            $subtotal_price = $carts->sum('price') * $carts->sum('quantity');

            if(!empty($data['voucher_id'])){
                $voucher_dis = $this->calculateVoucherDiscount($data['voucher_id'],$subtotal_price);
                $subtotal_price = $subtotal_price - $voucher_dis;
            }

            $orderData = [
                'user_id' => Auth::id(),
                'transaction_id' => uniqid(),
                'order_key' => uniqid(),
                'delivery_option_id' => $data['delivery_option_id'],
                'payment_method_id' => $data['payment_method_id'],
                'user_address_id' => $data['user_address_id'],
                'voucher_id' => $data['voucher_id'] ?? null,
                'shipping_amount' => $data['shipping_amount'],
                'subtotal_price' => $subtotal_price,
                'discount_rate' => $total_discountRate,
                'total_price' => $subtotal_price + $data['shipping_amount'] ?? 0,
                'status' => 'pending',
            ];

            $order = Order::create($orderData);
            $orderDetails = [];
            foreach ($carts as $p) {
                $product_p = Product::find($p['product_id']);
                $subtotal_p = $product_p->price;
                $product_color_p = ProductColor::find($p['product_color_id']);
                $subtotal_p += $product_color_p->price * $p['quantity'];

                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $product_p->id,
                    'product_color_id' => $p['product_color_id'],
                    'price' => $product_p->price,
                    'discount_rate' => $product_p->discount_rate,
                    'subtotal_price' => $subtotal_p,
                    'quantity' => $p['quantity'],
                    'total' => $subtotal_p + $data['shipping_amount'] ?? 0,
                ];
            }
            if ($order) {
                OrderDetails::insert($orderDetails);
                Cart::where('user_id', Auth::id())->delete();
                if ($data['payment_method_id'] == 2) {
                    if ($isProcessPayment = $this->processPayment($orderData)) {
                        DB::commit();
                        return [
                            'status' => true,
                            'message' => 'Payment Successful',
                            'data' => json_decode($isProcessPayment)
                        ];
                    } else {
                        DB::rollBack();
                        return [
                            'status' => false,
                            'message' => 'Order Unsuccessful',
                        ];
                    }
                } else {
                    DB::commit();
                    return [
                        'status' => true,
                        'message' => 'Payment Successful',
                    ];
                }
            } else {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => 'Order Unsuccessful',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function buyNowOrderStore($data)
    {
        DB::beginTransaction();
        try {
            $sub_price = 0;
            $product = Product::find($data['product_id']);
            $product->stock = $product->stock - $data['quantity'];
            $product->save();
            if ($product->discount_rate) {
                if ($product->discount_rate == 100) {
                    $pp = 0;
                } else {
                    $pp = $product->price - (($product->price * $product->discount_rate) / 100);
                }
                $sub_price = $pp * $data['quantity'];
            } else {
                $sub_price = $product->price * $data['quantity'];
            }
            $product_color = ProductColor::where('product_id', $data['product_id'])
                ->where('id', $data['product_color_id'])
                ->first();
            if ($product_color) {
                $sub_price += $product_color->price * $data['quantity'];
                $product_color->stock = $product_color->stock - $data['quantity'];
                $product_color->save();
            }
            if (!empty($data['product_feature_id'])) {
                $product_feature = ProductFeatureValue::whereIn('id', json_decode($data['product_feature_id']))->get();
                if ($product_feature) {
                    $total_feature = $product_feature->sum('price');
                    $sub_price += $total_feature * $data['quantity'];

                    foreach ($product_feature as $f) {
                        $ff = ProductFeatureValue::find($f['id']);
                        $ff->stock = $f['stock'] - $data['quantity'];
                        $ff->save();
                    }
                }
            }

            if(!empty($data['voucher_id'])){
                $voucher_dis = $this->calculateVoucherDiscount($data['voucher_id'],$sub_price);
                $sub_price = $sub_price - $voucher_dis;
            }

            $orderData = [
                'user_id' => Auth::id(),
                'payment_method_id' => $data['payment_method_id'],
                'delivery_option_id' => $data['delivery_option_id'],
                'user_address_id' => $data['user_address_id'],
                'voucher_id' => $data['voucher_id'] ?? null,
                'transaction_id' => uniqid(),
                'order_key' => uniqid(),
                'discount_rate' => $product->discount_rate,
                'shipping_amount' => $data['shipping_amount'],
                'subtotal_price' => $sub_price,
                'total_price' => $sub_price + $data['shipping_amount'] ?? 0,
                'order_note' => $data['order_note'] ?? null,
                'status' => 'pending',
            ];
            $order = Order::create($orderData);

            if ($order) {
                $orderDetails = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_color_id' => $product_color->id,
                    'price' => $product->price,
                    'discount_rate' => $product->discount_rate,
                    'subtotal_price' => $sub_price,
                    'quantity' => $data['quantity'],
                    'total' => $sub_price + $data['shipping_amount'] ?? 0,
                ];
                OrderDetails::insert($orderDetails);

                if ($data['payment_method_id'] == 2) {
                    if ($isProcessPayment = $this->processPayment($orderData)) {
                        DB::commit();
                        return [
                            'status' => true,
                            'message' => 'Payment Successful',
                            'data' => json_decode($isProcessPayment)
                        ];
                    } else {
                        DB::rollBack();
                        return [
                            'status' => false,
                            'message' => 'Order Unsuccessful',
                        ];
                    }
                } else {
                    DB::commit();
                    return [
                        'status' => true,
                        'message' => 'Payment Successful',
                    ];
                }
            } else {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => 'Order Unsuccessful',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function getUserOrderList()
    {
        return Order::where('user_id', Auth::id())->with(['orderDetails.product'])->get();
    }

    public function buyNowProduct($request)
    {
        try {
            $buyNowProduct = Product::where('id', $request->product_id)
                ->whereHas('colors', function ($query) use ($request) {
                    $query->where('id', $request->product_color_id);
                })->with(['colors' => function ($query) use ($request) {
                    $query->where('id', $request->product_color_id);
                }])->first();
            if ($buyNowProduct) {
                return $buyNowProduct;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function buyNowRequest($data)
    {
        DB::beginTransaction();
        try {
            $products = Product::where('id', $data->product_id)->first();
            $total_discountRate = $products->discount_rate;
            $subtotal_price = $this->calculateDiscountPrice($products->price, $products->discount_rate);
            $orderData = [
                'user_id' => Auth::id(),
                'transaction_id' => uniqid(),
                'order_key' => uniqid(),
                'delivery_option_id' => $data['delivery_option_id'],
                'payment_method_id' => $data['payment_method_id'],
                'user_address_id' => $data['user_address_id'] ?? null,
                'showroom_id' => $data['showroom_id'] ?? null,
                'shipping_amount' => $data['shipping_amount'] ?? 0,
                'subtotal_price' => $subtotal_price,
                'discount_rate' => $total_discountRate,
                'total_price' => $subtotal_price + $data['shipping_amount'] ?? 0,
                'status' => 1,
            ];
            $order = Order::create($orderData);
            $orderDetails = [
                'order_id' => $order->id,
                'product_id' => $products->id,
                'product_color_id' => $data['product_color_id'],
                'price' => $products->price,
                'discount_rate' => $total_discountRate,
                'subtotal_price' => $subtotal_price,
                'quantity' => 1,
                'total' => $subtotal_price + $data['shipping_amount'] ?? 0,
            ];
            if ($order) {
                OrderDetails::create($orderDetails);
                if ($data['payment_method_id'] == 2) {
                    if ($isProcessPayment = $this->processPayment($orderData)) {
                        DB::commit();
                        return [
                            'status' => true,
                            'message' => 'Payment Successful',
                            'data' => json_decode($isProcessPayment)
                        ];
                    } else {
                        return [
                            'status' => false,
                            'message' => 'Order Unsuccessful',
                        ];
                    }
                } else {
                    DB::commit();
                    return [
                        'status' => true,
                        'message' => 'Payment Successful',
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'Order Unsuccessful',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

//    public function buyNowProductPrice($request)
//    {
//        $buyNowProduct = $this->buyNowProduct($request);
//        $buyNowProductPrice = $buyNowProduct->price;
//        $buyNowProductDiscountRate = $buyNowProduct->discount_rate;
//        return $buyNowProductPrice - ($buyNowProductPrice * $buyNowProductDiscountRate / 100);
//    }

    public function voucherDiscountCalculate($data)
    {
        try {
            $data = Voucher::where('code', $data['code'])
                ->where('expires_at', '>', Carbon::parse(now()->addHours(6))->format('Y-m-d H:i:s'))
                ->where('status', 1)
                ->first();

            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function calculateVoucherDiscount($id, $amount)
    {
        $value = $amount;
        $voucher = Voucher::where('id', $id)
            ->where('expires_at', '>', Carbon::parse(now()->addHours(6))->format('Y-m-d H:i:s'))
            ->where('status', 1)
            ->first();
        if($voucher){
            if ($voucher->type == "percentage") {
                $value = (($value * $voucher->value) / 100);
            } else {
                $value = $voucher->value;
            }
            return $value;
        } else {
            return 0;
        }
    }
}
