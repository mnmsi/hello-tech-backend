<?php

namespace Modules\Api\Http\Controllers\Guest;

use App\Http\Controllers\AmarPayController;
use App\Http\Controllers\Controller;
use App\Models\GuestOrder;
use App\Models\GuestOrderDetails;
use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use App\Models\ProductFeatureValue;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Requests\Guest\GuestOrderRequest;
use Modules\Api\Http\Traits\Order\OrderTrait;
use Modules\Api\Http\Traits\Response\ApiResponseHelper;
use Session;

class GuestOrderController extends Controller
{
    use ApiResponseHelper;
    use OrderTrait;

    public function buyNow(GuestOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::where('id', $request->product_id)->first();
            $subtotal_price = $this->calculateDiscountPrice($product->price, $product->discount_rate) * $request->quantity;
            if (!empty($request->voucher_id)) {
                $calculateVoucher = $this->calculateVoucherDiscount($request->voucher_id, $subtotal_price);
                $subtotal_price = -$calculateVoucher;
            }
            if ($request->color_id) {
                $product_color = ProductColor::find($request->color_id);
                if ($product_color) {
                    if ($product_color->stock > 0) {
                        $subtotal_price += $product_color->price;
                        ProductColor::where('id', $request->color_id)->update([
                            'stock' => $product_color->stock - $request->quantity
                        ]);
                    } else {
                        return $this->respondError(
                            'Product is out of stock'
                        );
                    }
                }
            }
            if ($request->feature_id) {
                $product_feature = ProductFeatureValue::find($request->feature_id);
                if ($product_feature) {
                    if ($product_feature->stock > 0) {
                        $subtotal_price += $product_feature->price;
                    } else {
                        return $this->respondError(
                            'Product is out of stock'
                        );
                    }
                }
            }
            $orderData = [
                'transaction_id' => uniqid(),
                'order_key' => uniqid(),
                'discount_rate' => $product->discount_rate ?? 0,
                'shipping_amount' => $request->shipping_amount,
                'subtotal_price' => $subtotal_price,
                'total_price' => $subtotal_price + $request->shipping_amount,
                'name' => $request->name,
                'phone_number' => $request->phone,
                'email' => $request->email ?? null,
                'city' => $request->city,
                'division' => $request->division,
                'area' => $request->area,
                'address_line' => $request->address_line,
                'delivery_option' => $request->delivery_option,
                'payment_method' => $request->payment_method,
                'order_note' => $request->order_note ?? null,
                'voucher_code' => $request->voucher_code ?? null,
            ];

            $order = GuestOrder::create($orderData);
            $orderDetails = [
                'guest_order_id' => $order->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'product_color_id' => $request->color_id ?? null,
                'feature' => $request->feature_id ?? null,
                'price' => $product->price,
                'discount_rate' => $product->discount_rate ?? 0,
                'subtotal_price' => $subtotal_price,
            ];
            if ($order) {
                GuestOrderDetails::create($orderDetails);
                $sslc = new AmarPayController();
                if ($request->payment_method_id == 2) {
                    if ($isProcessPayment = $sslc->payment($orderData)) {
                        DB::commit();
                        return [
                            'status' => 'success',
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
                        'status' => true,
                        'message' => 'Payment Successful',
                    ];
                }
            } else {
                DB::rollBack();
                return $this->respondError(
                    'Something went wrong'
                );
            }
        } catch (\Exception $e) {
            return $this->respondError(
                $e->getMessage()
            );
        }
    }

    public function buyNowFromCart(Request $request)
    {
        try {
            DB::beginTransaction();
//        buy now from cart session
            $cart = Session::get('cart');
            $selected_cart = array_filter($cart, function ($item) {
                return $item['checked'] == true;
            });
            foreach ($selected_cart as $key => $cartItem) {
                $product = Product::find($key);
                $subtotal_price = $this->calculateDiscountPrice($product->price, $product->discount_rate) * $cartItem['quantity'];
//            product color price
                $product_color = ProductColor::find($cartItem['product_color_id']);
                if ($product_color) {
                    $subtotal_price += $product_color->price * $cartItem['quantity'];
                    if ($product_color->stock < $cartItem['quantity']) {
                        throw new \Exception('Product color out of stock.');
                    }
                    $product_color->stock = $product_color->stock - $cartItem['quantity'];
                    $product_color->save();
                }
                if (!empty($cartItem['product_data_id'])) {
                    $product_feature = ProductFeatureValue::whereIn('id', json_decode($cartItem['product_data_id']))->get();
                    if ($product_feature) {
                        $total_feature = $product_feature->sum('price');
                        $subtotal_price += $total_feature * $cartItem['quantity'];
                        foreach ($product_feature as $f) {
                            $ff = ProductFeatureValue::find($f['id']);
                            if ($f['stock'] < $cartItem['quantity']) {
                                throw new \Exception('Feature Product out of stock.');
                            }
                            $ff->stock = $f['stock'] - $cartItem['quantity'];
                            $ff->save();
                        }
                    }
                }
            }
            if (!empty($request['voucher_id'])) {
                $voucher_dis = $this->calculateVoucherDiscount($request['voucher_id'], $subtotal_price);
                $subtotal_price = $subtotal_price - $voucher_dis;
            }
            $orderData = [
                'transaction_id' => uniqid(),
                'order_key' => uniqid(),
                'discount_rate' => $product->discount_rate ?? 0,
                'shipping_amount' => $request->shipping_amount,
                'subtotal_price' => $subtotal_price,
                'total_price' => $subtotal_price + $request->shipping_amount,
                'name' => $request->name,
                'phone_number' => $request->phone,
                'email' => $request->email ?? null,
                'city' => $request->city,
                'division' => $request->division,
                'area' => $request->area,
                'address_line' => $request->address_line,
                'delivery_option' => $request->delivery_option,
                'payment_method' => $request->payment_method,
                'order_note' => $request->order_note ?? null,
                'voucher_code' => $request->voucher_code ?? null,
            ];
            $order = GuestOrder::create($orderData);
            $orderDetails = [
                'guest_order_id' => $order->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'product_color_id' => $request->color_id ?? null,
                'feature' => $request->feature_id ?? null,
                'price' => $product->price,
                'discount_rate' => $product->discount_rate ?? 0,
                'subtotal_price' => $subtotal_price,
            ];
            if ($order) {
                GuestOrderDetails::create($orderDetails);
                $sslc = new AmarPayController();
                if ($request->payment_method_id == 2) {
                    if ($isProcessPayment = $sslc->payment($orderData)) {
                        DB::commit();
                        return [
                            'status' => 'success',
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
                        'status' => true,
                        'message' => 'Payment Successful',
                    ];
                }
            } else {
                DB::rollBack();
                return $this->respondError(
                    'Something went wrong'
                );
            }
        } catch (\Exception $e) {
            return $this->respondError(
                $e->getMessage()
            );
        }

    }
}
