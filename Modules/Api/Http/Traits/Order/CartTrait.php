<?php

namespace Modules\Api\Http\Traits\Order;

use App\Models\Order\Cart;
use App\Models\ProductFeatureValue;
use Closure;
use Illuminate\Support\Arr;
use Modules\Api\Http\Resources\Cart\CartResource;
use Modules\Api\Http\Resources\Product\BrandResource;
use Modules\Api\Http\Resources\Product\ColorResource;

trait CartTrait
{

    /**
     * @return mixed
     */

//    total price of cart

    /**
     * @return array|mixed
     */
    public function getCartedData()
    {
        return Cart::wherehas('productColor', function ($q) {
            $q->where('stock', '>', 0);
        })->with(['product'])->where('user_id', auth()->id())->get();

    }

    /**
     * @param $data
     * @return array|Closure|mixed|object
     */
    public function addProductToCart($request): mixed
    {
//        dd($request->all());
        $request->merge(['user_id' => auth()->id()]);
        try {
            $cart = Cart::where('product_id', $request->product_id)->where('user_id', auth()->id())->first();
            if ($cart) {
                return false;
            } else {
                return Cart::create($request->all());
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $cart
     * @param $data
     * @return bool
     */
    public function checkExistingCartProduct($cart, $data)
    {
        return count($this->getExistingCartProduct($cart, $data)) > 0;
    }

    /**
     * @param $id
     * @return bool
     */

    public function removeProductFromCart($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->delete();
            return true;
        } else {
            return false;
        }
    }

    public function updateCartProduct($request)
    {
        $cart = Cart::where('id', $request->id)->where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->update($request->all());
            return $cart;
        } else {
            return false;
        }
    }

    /**
     * @return array
     *
     */
    public function getSelectedCartProduct()
    {
        return Cart::wherehas('productColor', function ($q) {
            $q->where('stock', '>', 0);
        })->where('user_id', auth()->id())->with('product')->where('status', '1')->get();
    }

    public function getSelectectedCartProductTotalPrice($carts)
    {
        $total_price = 0;
        foreach ($carts as $cart) {
            $total_price += $cart->product->price * $cart->quantity;
        }
        return $total_price;
    }

    public function calculateCardAmount($item)
    {
        $feature_price = 0;
        if ($this->product_data) {
            $feature_value = ProductFeatureValue::whereIn('id', json_decode($this->product_data, true))->get();
            $feature_price = $feature_value->sum('price');
        }
        return ($this->calculateDiscountPrice($item->product->price, $item->product->discount_rate ?? 0) + $feature_price + $item->productColor->price) * $item->quantity;
    }


}
