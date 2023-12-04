<?php

namespace Modules\Api\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Api\Http\Requests\Order\AddCartRequest;
use Modules\Api\Http\Requests\Order\CreateOrderRequest;
use Modules\Api\Http\Resources\Order\OrderListResource;
use Modules\Api\Http\Resources\Order\OrderResource;
use Modules\Api\Http\Traits\Order\OrderTrait;
use Modules\Api\Http\Traits\Payment\PaymentTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;

class OrderController extends Controller
{
    use ProductTrait;
    use PaymentTrait;
    use OrderTrait;

    /**
     * Get Delivery Options
     *
     * @return JsonResponse
     */
    public function deliveryOptions(): JsonResponse
    {
        return $this->respondWithSuccessWithData(
            $this->getDeliveryOptions()
        );
    }

    /**
     * Get Payment Methods
     *
     * @return JsonResponse
     */
    public function paymentMethods(): JsonResponse
    {
        return $this->respondWithSuccessWithData(
            $this->getPaymentMethods()
        );
    }

    public function order(CreateOrderRequest $request)
    {
        if ($request->cart_id) {
            $order = $this->storeOrder($request);
        } else {
            $order = $this->buyNowOrderStore($request);
        }

        return $order;
    }

    public function orderList()
    {
        $orders = $this->getUserOrderList();
        if ($orders) {
            return $this->respondWithSuccessWithData(
               OrderListResource::collection($orders)
            );
        } else {
            return $this->respondError(
                "Something went wrong"
            );
        }
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $cart = $this->buyNowProduct($request);
        if ($cart) {
            return $this->respondWithSuccess([
                'data' => [new OrderResource($cart)],
                'total_price' => $this->buyNowProductPrice($request),
            ]);
        } else {
            return $this->respondError(
                "Something went wrong"
            );
        }
    }

    public function makeOrderFromBuyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_color_id' => 'required|exists:product_colors,id',
            'quantity' => 'required|numeric',
            'voucher_id' => 'nullable|exists:vouchers,id',
        ]);
        $order = $this->buyNowRequest($request);
        if ($order) {
            return $this->respondWithSuccessWithData(
                $order
            );
        } else {
            return $this->respondError(
                "Something went wrong!"
            );
        }
    }

    //    voucher get
    public function getVoucherDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:vouchers,code',
            'amount' => 'required|min:0',
        ]);
        $discount = $this->voucherDiscountCalculate($request);
        if ($discount) {
            $result = [
                'id' => $discount->id,
                'code' => $discount->code,
                'value' => $discount->value,
                'type' => $discount->type,
                'amount' => $request['amount'],
            ];

            if ($discount->type == "amount") {
                $result['discount_amount'] = $request['amount'] - $discount->value;
            } else {
                $result['discount_amount'] = $request['amount'] - (($discount->value * $request['amount']) / 100);
            }
            return $this->respondWithSuccessWithData(
                $result
            );
        } else {
            return $this->respondError(
                "Something went wrong!"
            );
        }
    }
}
