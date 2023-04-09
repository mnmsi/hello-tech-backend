<?php

namespace Modules\Api\Http\Traits\Order;

use Closure;
use Illuminate\Support\Arr;
use Modules\Api\Http\Resources\Product\BrandResource;
use Modules\Api\Http\Resources\Product\ColorResource;

trait CartTrait
{
    /**
     * @return array|mixed
     */
    public function getCartedData()
    {
        // Get cookie data
        $cartData = request()->cookie('cart');

        // Decode json data
        return json_decode($cartData, true) ?: [];
    }

    /**
     * @param $data
     * @return array|Closure|mixed|object
     */
    public function addProductToCart($data)
    {
        // Get carted data
        $cart = $this->getCartedData();

        // Check if product already exists in cart
        if ($this->checkExistingCartProduct($cart, $data)) {

            // Update existing cart product quantity
            $cart = $this->updateExistingCartProductQuantity($cart, $data);

        } else {

            // Check if quantity is less than 0
            if ($data['quantity'] < 0) {
                return [
                    'error' => 'Quantity can not be less than 0'
                ];
            }

            // Add new product to cart
            $cart[] = $this->addNewProduct($data);
        }

        return $cart;
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
     * @param $cart
     * @param $data
     * @return array
     */
    public function getExistingCartProduct($cart, $data)
    {
        // Get existing cart product by product id and color id
        return Arr::where($cart, function ($value) use ($data) {

            // Check if sku is set
            if (is_string($data)) {
                return $value['sku'] == $data;
            }

            // Check if product id and color id is set
            return $value['product_id'] == $data['product_id'] && $value['product_color_id'] == $data['product_color_id'];
        });
    }

    /**
     * @param $data
     * @return array
     */
    public function addNewProduct($data)
    {
        // Add new product to cart array
        return [
            'sku'              => "sku-" . uniqid(),
            'product_id'       => $data['product_id'],
            'product_color_id' => $data['product_color_id'],
            'quantity'         => $data['quantity'],
        ];
    }

    /**
     * @param $cart
     * @param $data
     * @return mixed|string[]
     */
    public function updateExistingCartProductQuantity($cart, $data)
    {
        // Update existing cart product quantity
        foreach ($this->getExistingCartProduct($cart, $data) as $key => $item) {

            // Sum quantity
            $qtn = $item['quantity'] + $data['quantity'];

            // Check if quantity is less than 0
            if ($qtn < 0) {
                return [
                    'error' => 'Quantity can not be less than 0'
                ];
            }

            // Remove product from cart if quantity is 0
            if ($qtn == 0) {
                Arr::forget($cart, $key);
                break;
            }

            $cart[$key]['quantity'] = $qtn;
        }

        return $cart;
    }

    /**
     * @param $carts
     * @return array
     */
    public function getCartedProductDetails($carts)
    {
        $cartedProdDetails = [];
        foreach ($carts as $cart) {
            // Get product details
            $product = $this->getProductDetails($cart['product_id']);
            $product->load('brand', 'colors');

            // Calculate discount price
            $discountPrice = $this->calculateDiscountPrice($product->price, $product->discount_rate);
            $color         = $product->colors->where('id', $cart['product_color_id'])->first();

            // Push product details to cartedProdDetails array
            $cartedProdDetails[] = [
                'sku'             => $cart['sku'],
                'product_id'      => $product->id,
                'product_name'    => $product->name,
                'image'           => $product->media->where('type', 'image')->first()->url ?? $product->image_url,
                'brand'           => new BrandResource($product->brand),
                'color'           => new ColorResource($color),
                'quantity'        => $cart['quantity'],
                'price'           => $product->price,
                'discount_rate'   => $product->discount_rate,
                'discount_price'  => $discountPrice,
                'total_price'     => $discountPrice * $cart['quantity'],
                'shipping_charge' => $product->shipping_charge,
                'stock'           => $color->stock,
            ];
        }

        // Return carted product details
        return $cartedProdDetails;
    }

    public function removeProductFromCart($cart, $key)
    {
        // Remove product from cart
        Arr::forget($cart, $key);

        return $cart;
    }

    public function returnResponseWithCookie($carts)
    {
        return response()
            ->json([
                'status' => true,
                'data'   => $this->getCartedProductDetails($carts), // Get carted product details
            ])->withCookie(
                cookie()
                    ->forever('cart', json_encode($carts)));
    }
}
