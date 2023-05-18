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

trait OrderTrait
{

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
            $products = Product::whereIn('id',$carts->pluck('product_id'));
            $total_discountRate = $products->sum('discount_rate');
            $subtotal_price = $carts->sum('price') * $carts->sum('quantity');
            $order = [
                'user_id' => Auth::id(),
                'transaction_id' => uniqid(),
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
            if ($data['payment_method_id'] == 2) {
              $this->processPayment($order);
            }
//            $order = Order::create($orderData);

            $orderDetails = [];
            foreach ($products as $product) {
                $discountRate = $product->discount_rate;
                $subtotal = $product->price * $product->quantity;

                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->product_id,
                    'product_color_id' => $product->product_color_id,
                    'price' => $product->price,
                    'discount_rate' => $discountRate,
                    'subtotal_price' => $subtotal,
                    'quantity' => $product->quantity,
                    'total' => $subtotal + $data['shipping_amount'] ?? 0,
                ];


                if ($order) {
                    Cart::whereIn('id', $cartIds)->delete();
                    OrderDetails::insert($orderDetails);
                    if ($data['payment_method_id'] == 2) {
                        if ($isProcessPayment = $this->processPayment($order)) {
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
}
