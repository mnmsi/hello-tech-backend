<?php

namespace Modules\Api\Http\Traits\Order;

use App\Models\Order\Cart;
use App\Models\Order\Order;
use App\Models\OrderDetails;
use App\Models\Product\Product;
use App\Models\System\DeliveryOption;
use App\Models\System\PaymentMethod;
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
            ->select('id', 'name', 'bonus')
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
            $cartIds = $data['cart_id'];
            $carts = Cart::whereIn('id', $cartIds)
                ->select('id', 'product_id', 'product_color_id', 'price', 'quantity')->get();
            $products = Product::whereIn('id', $carts->pluck('product_id'))->with('colors')->get();
            $total_discountRate = $products->sum('discount_rate');
            $subtotal_price = $carts->sum('price') * $carts->sum('quantity');
            $carts->map(function ($value, $key) {
                return $value['product_id'] == 1;
            });
            $orderData = [
                'user_id' => Auth::id(),
                'transaction_id' => uniqid(),
                'order_key' => uniqid(),
                'delivery_option_id' => $data['delivery_option_id'],
                'payment_method_id' => $data['payment_method_id'],
                'user_address_id' => $data['user_address_id'],
                'showroom_id' => $data['showroom_id'],
                'shipping_amount' => $data['shipping_amount'],
                'subtotal_price' => $subtotal_price,
                'discount_rate' => $total_discountRate,
                'total_price' => $subtotal_price + $data['shipping_amount'] ?? 0,
                'status' => 1,
            ];
            $order = Order::create($orderData);
            $orderDetails = [];
            foreach ($products as $product) {
                $discountRate = $product->discount_rate;
                $subtotal = $product->price * count($products);
                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_color_id' => collect($carts)->filter(function ($value, $key) use ($product) {
                        return $value['product_id'] == $product->id;
                    })->first()->product_color_id,
                    'price' => $product->price,
                    'discount_rate' => $discountRate,
                    'subtotal_price' => $subtotal,
                    'quantity' => count($products),
                    'total' => $subtotal + $data['shipping_amount'] ?? 0,
                ];
                if ($order) {
                    OrderDetails::insert($orderDetails);
                    Cart::whereIn('id', $cartIds)->delete();
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
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function getUserOrderList()
    {
        return Order::where('user_id', Auth::id())->get();
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

    public function buyNowProductPrice($request)
    {
        $buyNowProduct = $this->buyNowProduct($request);
        $buyNowProductPrice = $buyNowProduct->price;
        $buyNowProductDiscountRate = $buyNowProduct->discount_rate;
        return $buyNowProductPrice - ($buyNowProductPrice * $buyNowProductDiscountRate / 100);
    }

}
