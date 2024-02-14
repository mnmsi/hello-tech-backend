<?php

namespace Modules\Api\Http\Traits\Order;

use App\Models\Order\Cart;
use App\Models\Order\Order;
use App\Models\OrderDetails;
use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use App\Models\ProductData;
use App\Models\ProductFeatureValue;
use App\Models\System\Area;
use App\Models\System\City;
use App\Models\System\DeliveryOption;
use App\Models\System\Division;
use App\Models\System\Notification;
use App\Models\System\PaymentMethod;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Traits\OTP\OtpTrait;
use Modules\Api\Http\Traits\Payment\PaymentTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;
use App\Http\Controllers\AmarPayController;

trait
OrderTrait
{
    use productTrait;
    use OtpTrait;

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
            $userCart = Cart::where('user_id', Auth::id())->where('status', 1);
            $carts = $userCart->get();
            $orderDetails = [];
            if ($carts->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            foreach ($carts as $cartItem) {

                $featurePrice = 0;
                $product_feature_id = [];
                $product = Product::with(['productFeatureValues', 'colors'])->where('id', $cartItem['product_id'])->first();

                if (isset($cartItem['product_data'])) {
                    $product_feature_id = json_decode($cartItem['product_data']);
                    $featurePrice = $product->productFeatureValues->whereIn('id', $product_feature_id)->sum('price');
                }

                $price = $product->price + $featurePrice + $product->colors->whereIn('id', $cartItem->product_color_id)->sum('price');
                $subtotal_price = $this->calculateDiscountPrice($price, $product->discount_rate) * $cartItem['quantity'];

                // product color price
                $product_color = $product->colors->whereIn('id', $cartItem->product_color_id)->first();
                if ($product_color) {
                    if ($product_color->stock < $cartItem['quantity']) {
                        throw new \Exception('Product color out of stock.');
                    }
                    $product_color->stock = $product_color->stock - $cartItem['quantity'];
                    $product_color->save();
                }

                if (!empty($cartItem['product_data'])) {
                    $product_features = $product_feature_id;
                    foreach ($product_features as $f) {
                        $productFeatureValue = ProductFeatureValue::find($f);
                        if ($productFeatureValue['stock'] < $cartItem['quantity']) {
                            throw new \Exception('Feature Product out of stock.');
                        }
                        $productFeatureValue->stock = $productFeatureValue['stock'] - $cartItem['quantity'];
                        $productFeatureValue->save();
                    }
                }

                $orderDetails[] = [
                    'product_id' => $cartItem['product_id'],
                    'product_color_id' => $cartItem['product_color_id'],
                    'price' => $price ?? 0,
                    'quantity' => $cartItem['quantity'] ?? 0,
                    'discount_rate' => $product->discount_rate ?? 0,
                    'subtotal_price' => $subtotal_price ?? 0,
                    'total' => $subtotal_price ?? 0,
                ];
            }

            $subtotal_price = collect($orderDetails)->sum('subtotal_price');

            if (!empty($data['voucher_id'])) {
                $voucher_dis = $this->calculateVoucherDiscount($data['voucher_id'], $subtotal_price);
                $subtotal_price = $subtotal_price - $voucher_dis;
            }

            $total_price = $subtotal_price + $data['shipping_amount'] ?? 0;
            $order_key = now()->format('Ymd') . '-' . Order::count() + 1;

            $orderData = [
                'user_id' => Auth::id(),
                'transaction_id' => $order_key,
                'order_key' => $order_key,
                'delivery_option_id' => $data['delivery_option_id'],
                'payment_method_id' => $data['payment_method_id'],
                'division' => Division::where('id', $data['division_id'])->first()->name,
                'city' => City::where('id', $data['city_id'])->first()->name,
                'area' => Area::where('id', $data['area_id'])->first()->name,
                'address_line' => $data['address_line'],
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'voucher_id' => $data['voucher_id'] ?? null,
                'shipping_amount' => $data['shipping_amount'],
                'discount_rate' => $data['discount_rate'] ?? 0,
                'subtotal_price' => $subtotal_price, // price without shipping cost
                'total_price' => $subtotal_price + $data['shipping_amount'] ?? 0, // price with shipping cost
                'status' => 'pending',
            ];

            $order = Order::create($orderData);
            if ($order) {
                $newOrderDetails = collect($orderDetails)->map(function ($item) use ($order) {
                    return array_merge($item, ['order_id' => $order->id]);
                })->toArray();

                OrderDetails::insert($newOrderDetails);

                $userCart->delete();

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
                    $numbers = Notification::where('status', 1)->get();
                    foreach ($numbers as $number) {
                        $this->sendSms(strtr($number->phone, [' ' => '']), "New order has been placed with the order number: " . $order->order_key . "  Please check your dashboard");
                    }
                    $message = "Hi! " . $data['name'] . ".  Your order has been placed successfully. Your order number is " . $order->order_key . " Total " . $total_price . " BDT.  Thank you for shopping from hellotech.store";
                    $this->sendSms($data['phone'], $message);
                    return [
                        'data' => [
                            'order_id' => $order->id,
                            'transaction_id' => $order->transaction_id,
                            'order_key' => $order->order_key,
                            'total' => $total_price,
                        ],
                        'status' => true,
                        'message' => 'Order Successful',
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
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function buyNowOrderStore($data)
    {

        DB::beginTransaction();
        try {

            $product_feature_id = $data['product_feature_id'] ?? [];
            if (isset($request->feature_value_id)) {
                foreach ($product_feature_id as $key => $value) {
                    $product_feature_id[$key] = (int)$value;
                }
            }

            $product = Product::with(['productFeatureValues', 'colors'])->where('id', $data['product_id'])->first();
            $price = $product->price + $product->productFeatureValues->whereIn('id', $product_feature_id)->sum('price') + $product->colors->whereIn('id', $data['product_color_id'])->sum('price');
            $subtotal_price = $this->calculateDiscountPrice($price, $product->discount_rate) * $data['quantity'];

            if (!empty($request->voucher_id)) {
                $calculateVoucher = $this->calculateVoucherDiscount($data['voucher_id'], $subtotal_price);
                $subtotal_price = -$calculateVoucher;
                $price = $price - $calculateVoucher;
            }

            $total_price = $subtotal_price + $data['shipping_amount'] ?? 0;

            if ($data['product_color_id']) {
                $product_color = ProductColor::find($data['product_color_id']);
                if ($product_color) {
                    if ($product_color->stock > 0) {
                        ProductColor::where('id', $data['product_color_id'])->update([
                            'stock' => $product_color->stock - $data['quantity']
                        ]);
                    } else {
                        return $this->respondError(
                            'Product is out of stock'
                        );
                    }
                }
            }

            if ($data['product_feature_id']) {
                $product_feature = ProductFeatureValue::WhereIn('id', json_decode($data['product_feature_id']))->get();
                if ($product_feature) {
                    ;
                    foreach ($product_feature as $f) {
                        if ($f->stock > 0) {
                            $f->stock = $f->stock - $data['quantity'];
                            $f->save();
                        } else {
                            return $this->respondError(
                                'Product feature is out of stock'
                            );
                        }
                    }
                }
            }
            $order_key = now()->format('Ymd') . '-' . Order::count() + 1;
            $orderData = [
                'user_id' => Auth::id(),
                'payment_method_id' => $data['payment_method_id'],
                'delivery_option_id' => $data['delivery_option_id'],
                'division' => Division::where('id', $data['division_id'])->first()->name,
                'city' => City::where('id', $data['city_id'])->first()->name,
                'area' => Area::where('id', $data['area_id'])->first()->name,
                'address_line' => $data['address_line'],
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'voucher_id' => $data['voucher_id'] ?? null,
                'transaction_id' => $order_key,
                'order_key' => $order_key,
                'discount_rate' => $product->discount_rate,
                'quantity' => $data['quantity'],
                'shipping_amount' => $data['shipping_amount'],
                'subtotal_price' => $subtotal_price,
                'total_price' => $subtotal_price + $data['shipping_amount'] ?? 0,
                'order_note' => $data['order_note'] ?? null,
                'status' => 'pending',
            ];

            $order = Order::create($orderData);

            if ($order) {
                $orderDetails = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_color_id' => $data['product_color_id'],
                    'price' => $price,
                    'discount_rate' => $product->discount_rate,
                    'subtotal_price' => $subtotal_price,
                    'quantity' => $data['quantity'],
                    'total' => $subtotal_price,
                ];

                OrderDetails::insert($orderDetails);

                $sslc = new AmarPayController();
                if ($data['payment_method_id'] == 2) {
                    if ($isProcessPayment = $sslc->payment($orderData)) {
                        DB::commit();
                        return [
                            'status' => true,
                            'message' => 'Payment Successful',
                            'data' => $isProcessPayment->getTargetUrl()
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
                    $numbers = Notification::where('status', 1)->get();
                    foreach ($numbers as $number) {
                        $this->sendSms(strtr($number->phone, [' ' => '']), "New order has been placed with the order number: " . $order->order_key . "  Please check your dashboard");
                    }
                    $message = "Hi! " . $data['name'] . ".  Your order has been placed successfully. Your order number is " . $order->order_key . " Total " . $total_price . " BDT.  Thank you for shopping from hellotech.store";
                    $this->sendSms($data['phone'], $message);
                    return [
                        'data' => [
                            'order_id' => $order->id,
                            'transaction_id' => $order->transaction_id,
                            'order_key' => $order->order_key,
                            'total' => $total_price,
                        ],
                        'status' => true,
                        'message' => 'Order Successful',
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
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getUserOrderList()
    {
        return Order::where('user_id', Auth::id())->with(['orderDetails.product', 'userAddress'])->latest()->get();
    }


    public function buyNowProduct($request)
    {
        try {
            $buyNowProduct = Product::where('id', $request->product_id)
                ->whereHas('colors', function ($query) use ($request) {
                    $query->where('id', $request->product_color_id);
                })->with(['colors' => function ($query) use ($request) {
                    $query->where('id', $request->product_color_id);
                }])->whereHas('productFeatureValues', function ($query) use ($request) {
                    $query->whereIn('id', json_decode($request->product_feature_id));
                })->with(['productFeatureValues' => function ($query) use ($request) {
                    $query->whereIn('id', json_decode($request->product_feature_id));
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
        if ($voucher) {
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
