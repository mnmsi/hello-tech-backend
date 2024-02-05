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
use App\Models\System\PaymentMethod;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Traits\Payment\PaymentTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;
use App\Http\Controllers\AmarPayController;

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
            //            cart list
            $carts = Cart::select('id', 'product_id', 'product_color_id', 'product_data', 'quantity')
                ->where('user_id', Auth::id())
                ->where('status', 1)
                ->get();

            $newOrderDetails = [];

            foreach ($carts as $c) {
                $product_price = 0;
                $sub_total = 0;
                // get product
                $product = Product::find($c['product_id']);
                if ($product->discount_rate) {
                    if ($product->discount_rate == 100) {
                        $pp = 0;
                    } else {
                        $pp = $product->price - (($product->price * $product->discount_rate) / 100);
                    }
                    $product_price = $pp * $c['quantity'];
                    $sub_total = $pp;
                } else {
                    $product_price = $product->price * $c['quantity'];
                    $sub_total = $product->price;
                }
                // product color price
                $product_color = ProductColor::find($c['product_color_id']);
                /*
                 *
                 * Product color
                 *
                 * */
                if ($product_color) {
                    if ($product_color->stock < $c['quantity']) {
                        throw new \Exception('Product color out of stock.');
                    }
                    $product_price += $product_color->price * $c['quantity'];
                    $sub_total += $product_color->price;
                    $product_color->stock = $product_color->stock - $c['quantity'];
                    $product_color->save();
                }

                /*
                 *
                 * feature data if exist
                 *
                 * */
                if (!empty($c['product_data'])) {
                    $product_feature = ProductFeatureValue::whereIn('id', json_decode($c['product_data']))->get();
                    if ($product_feature) {
                        $total_feature = $product_feature->sum('price');
                        $product_price += $total_feature * $c['quantity'];
                        $sub_total += $total_feature;

                        foreach ($product_feature as $f) {
                            $ff = ProductFeatureValue::find($f['id']);
                            if ($f['stock'] < $c['quantity']) {
                                throw new \Exception('Feature Product out of stock.');
                            }
                            $ff->stock = $f['stock'] - $c['quantity'];
                            $ff->save();
                        }
                    }
                }

                $newOrderDetails[] = [
                    "product_id" => $c['product_id'],
                    "product_color_id" => $c['product_color_id'],
                    "price" => $product->price, // product price
                    "quantity" => $c['quantity'], // quantity
                    "discount_rate" => $product->discount_rate, // product discount rate
                    "subtotal_price" => $sub_total, // without quantity
                    "total" => $sub_total * $c['quantity'], // product + product color + product feature + discount + quantity
                ];
            }
            $subtotal_price = collect($newOrderDetails)->sum("total");
            if (!empty($data['voucher_id'])) {
                $voucher_dis = $this->calculateVoucherDiscount($data['voucher_id'], $subtotal_price);
                $subtotal_price = $subtotal_price - $voucher_dis;
            }

            $orderData = [
                'user_id' => Auth::id(),
                'transaction_id' => uniqid(),
                'order_key' => uniqid(),
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
                'discount_rate' => 0,
                'subtotal_price' => $subtotal_price, // price without shipping cost
                'total_price' => $subtotal_price + $data['shipping_amount'] ?? 0,
                'status' => 'pending',
            ];

            $order = Order::create($orderData);

            if ($order) {
                $order_details_list = collect($newOrderDetails)->map(function ($item) use ($order) {
                    return array_merge($item, ['order_id' => $order->id]);
                })->toArray();
                OrderDetails::insert($order_details_list);
                Cart::where('user_id', Auth::id())->where('status',1)->delete();
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
                        'data' => [
                            'order_id' => $order->id,
                            'transaction_id' => $order->transaction_id,
                            'order_key' => $order->order_key,
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

//    public function storeOrder($data)
//    {
//        DB::beginTransaction();
//        try {
//            $total_discountRate = 0;
//            $subtotal_price = 0;
//            //            cart list
//            $carts = Cart::select('id', 'product_id', 'product_color_id', 'product_data', 'quantity')
//                ->where('user_id', Auth::id())
//                ->where('status', 1)
//                ->get();
//
//            foreach ($carts as $c) {
//                //                get product
//                $product = Product::find($c['product_id']);
//                //                product color price
//                $product_color = ProductColor::find($c['product_color_id']);
//                //                price calculation
//                if ($product->discount_rate) {
//                    if ($product->discount_rate == 100) {
//                        $pp = 0;
//                        $total_discountRate += 100;
//                    } else {
//                        $pp = $product->price - (($product->price * $product->discount_rate) / 100);
//                        $total_discountRate += $product->discount_rate;
//                    }
//                    $subtotal_price = $pp * $c['quantity'];
//                } else {
//                    $subtotal_price = $product->price * $c['quantity'];
//                }
//
//                if ($product_color) {
//                    $subtotal_price += $product_color->price * $c['quantity'];
//                    if ($product_color->stock < $c['quantity']) {
//                        throw new \Exception('Product color out of stock.');
//                    }
//                    $product_color->stock = $product_color->stock - $c['quantity'];
//                    $product_color->save();
//                }
//                /*
//                 * feature data if exist
//                 * */
//                //                product feature
//                if (!empty($c['product_data'])) {
//                    $product_feature = ProductFeatureValue::whereIn('id', json_decode($c['product_data']))->get();
//                    if ($product_feature) {
//                        $total_feature = $product_feature->sum('price');
//                        $subtotal_price += $total_feature * $c['quantity'];
//
//                        foreach ($product_feature as $f) {
//                            $ff = ProductFeatureValue::find($f['id']);
//                            if ($f['stock'] < $c['quantity']) {
//                                throw new \Exception('Feature Product out of stock.');
//                            }
//                            $ff->stock = $f['stock'] - $c['quantity'];
//                            $ff->save();
//                        }
//                    }
//                }
//            }
//
//            if (!empty($data['voucher_id'])) {
//                $voucher_dis = $this->calculateVoucherDiscount($data['voucher_id'], $subtotal_price);
//                $subtotal_price = $subtotal_price - $voucher_dis;
//            }
//
//            $orderData = [
//                'user_id' => Auth::id(),
//                'transaction_id' => uniqid(),
//                'order_key' => uniqid(),
//                'delivery_option_id' => $data['delivery_option_id'],
//                'payment_method_id' => $data['payment_method_id'],
//                'division' => Division::where('id', $data['division_id'])->first()->name,
//                'city' => City::where('id', $data['city_id'])->first()->name,
//                'area' => Area::where('id', $data['area_id'])->first()->name,
//                'address_line' => $data['address_line'],
//                'name' => $data['name'],
//                'phone' => $data['phone'],
//                'email' => $data['email'] ?? null,
//                'voucher_id' => $data['voucher_id'] ?? null,
//                'shipping_amount' => $data['shipping_amount'],
//                'subtotal_price' => $subtotal_price,
//                'discount_rate' => $total_discountRate,
//                'total_price' => $subtotal_price + $data['shipping_amount'] ?? 0,
//                'status' => 'pending',
//            ];
//
//            $order = Order::create($orderData);
//            $orderDetails = [];
//            foreach ($carts as $p) {
//                $product_p = Product::find($p['product_id']);
//                $subtotal_p = $product_p->price;
//                $product_color_p = ProductColor::find($p['product_color_id']);
//                $subtotal_p += $product_color_p->price * $p['quantity'];
//
//                $orderDetails[] = [
//                    'order_id' => $order->id,
//                    'product_id' => $product_p->id,
//                    'product_color_id' => $p['product_color_id'],
//                    'price' => $product_p->price,
//                    'discount_rate' => $product_p->discount_rate,
//                    'subtotal_price' => $subtotal_p,
//                    'quantity' => $p['quantity'],
//                    'total' => $subtotal_p + $data['shipping_amount'] ?? 0,
//                ];
//            }
//            if ($order) {
//                OrderDetails::insert($orderDetails);
//                Cart::where('user_id', Auth::id())->delete();
//                if ($data['payment_method_id'] == 2) {
//                    if ($isProcessPayment = $this->processPayment($orderData)) {
//                        DB::commit();
//                        return [
//                            'status' => true,
//                            'message' => 'Payment Successful',
//                            'data' => json_decode($isProcessPayment)
//                        ];
//                    } else {
//                        DB::rollBack();
//                        return [
//                            'status' => false,
//                            'message' => 'Order Unsuccessful',
//                        ];
//                    }
//                } else {
//                    DB::commit();
//                    return [
//                        'status' => true,
//                        'message' => 'Payment Successful',
//                    ];
//                }
//            } else {
//                DB::rollBack();
//                return [
//                    'status' => false,
//                    'message' => 'Order Unsuccessful',
//                ];
//            }
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return [
//                'status' => false,
//                'message' => $e->getMessage(),
//            ];
//        }
//    }

    public function buyNowOrderStore($data)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($data['product_id']);

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
                if ($product_color->stock < $data['quantity']) {
                    throw new \Exception('Product color out of stock.');
                }
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
                        if ($f['stock'] < $data['quantity']) {
                            throw new \Exception('Feature product color out of stock.');
                        }
                        $ff->stock = $f['stock'] - $data['quantity'];
                        $ff->save();
                    }
                }
            }
            if (!empty($data['voucher_id'])) {
                $voucher_dis = $this->calculateVoucherDiscount($data['voucher_id'], $sub_price);
                $sub_price = $sub_price - $voucher_dis;
            }
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
                    return [
                        'data' => [
                            'order_id' => $order->id,
                            'transaction_id' => $order->transaction_id,
                            'order_key' => $order->order_key,
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
                'division' => Division::where('id', $data['division_id'])->first()->name,
                'city' => City::where('id', $data['city_id'])->first()->name,
                'area' => Area::where('id', $data['area_id'])->first()->name,
                'address_line' => $data['address_line'],
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'voucher_id' => $data['voucher_id'] ?? null,
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
                'product_data' => $data['product_feature_id'] ?? '',
                'price' => $products->price,
                'discount_rate' => $total_discountRate,
                'subtotal_price' => $subtotal_price,
                'quantity' => 1,
                'total' => $subtotal_price + $data['shipping_amount'] ?? 0,
            ];
            if ($order) {
                OrderDetails::create($orderDetails);
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
                        return [
                            'status' => false,
                            'message' => 'Order Unsuccessful',
                        ];
                    }
                } else {
                    DB::commit();
                    return [
                        'data' => [
                            'order_id' => $order->id,
                            'transaction_id' => $order->transaction_id,
                            'order_key' => $order->order_key,
                        ],
                        'status' => true,
                        'message' => 'Order Successful',
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
