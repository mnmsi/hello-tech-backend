<?php

namespace Modules\Api\Http\Traits\Order;

use App\Models\Order\Cart;
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
        return Cart::with(['product', 'productColor',])->where('user_id', auth()->id())->get();

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
        return Cart::where('user_id', auth()->id())->with('product')->where('status', '1')->get();
    }


}
