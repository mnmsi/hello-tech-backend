<?php

namespace Modules\Api\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\ProductFeatureValue;
use Illuminate\Http\Request;
use Modules\Api\Http\Requests\Order\AddCartRequest;
use Modules\Api\Http\Traits\Cart\GuestCartTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;
use Modules\Api\Http\Traits\Response\ApiResponseHelper;
use Session;

class GuestCartController extends Controller
{
    use GuestCartTrait;
    use ApiResponseHelper;
    use ProductTrait;

    public function store(AddCartRequest $request)
    {
//        Session::flush();
        // add to cart
        $cart = $request->session()->get('cart', []);
        if (array_key_exists($request->product_id, $cart)) {
            return $this->respondError('Product already added to cart');
        } else {
            $cart[$request->product_id] = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'product_color_id' => $request->product_color_id ?? null,
                'product_data_id' => $request->product_data_id ?? null,
                'checked' => $request->checked ?? false,
            ];
        }
        $request->session()->put('cart', $cart);
        return $this->respondWithSuccess(['cart' => $cart]);
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return $this->respondWithSuccess(['cart' => $cart]);
    }

    public function getCartProduct()
    {
        $cart = session()->get('cart', []);
        if (isset($cart) && count($cart) > 0) {
            return $this->extracted($cart);
        } else {
            return $this->respondWithSuccess(['products' => []]);
        }
    }

    public function updateCart(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (array_key_exists($request->product_id, $cart)) {
            $cart[$request->product_id] = [
                'product_color_id' => $request->product_color_id ?? null,
                'product_data_id' => $request->product_data_id ?? null,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'checked' => $request->checked ?? false,
            ];
            $request->session()->put('cart', $cart);
            return $this->respondWithSuccess(['cart' => $cart]);
        } else {
            return $this->respondError('Product not found in cart');
        }

    }

    public function deleteCart(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$request->product_id]);
        $request->session()->put('cart', $cart);
        return $this->respondWithSuccess(['cart' => $cart]);
    }

    public function getSelectedProduct()
    {
        $cart = session()->get('cart', []);
        if ($cart) {
            $filter_cart = array_filter($cart, function ($e) {
                return $e['checked'] == true;
            });
            return $this->extracted($filter_cart);
        }
    }

    /**
     * @param mixed $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function extracted(mixed $cart): \Illuminate\Http\JsonResponse
    {
        $products = Product::with(['colors' => function ($e) use ($cart) {
            return $e->where('id', array_column($cart, 'product_color_id'))->where('stock', '>', 0);
        }, "productFeatureValues" => function ($e) use ($cart) {
            return $e->whereIn('product_feature_values.id', array_column($cart, 'product_data_id'));
        }])->whereIn('id', array_keys($cart))->get();
        $data = [];
        foreach ($products as $key => $product) {
            $data[] = [
                'id' => $key,
                'product_id' => $product->id,
                'checked' => $cart[$product->id]['checked'] ?? false,
                'name' => $product->name,
                'image_url' => asset('storage/' . $product->image_url),
                'quantity' => $cart[$product->id]['quantity'],
                'product_color_id' => $cart[$product->id]['product_color_id'],
                'color_name' => $product->colors->first()->name ?? '',
                'price' => collect($product->productFeatureValues)->sum('price') + $this->calculateDiscountPrice($product->price, $product->discount_rate) + collect($product->colors)->sum('price') * $cart[$product->id]['quantity'],
            ];
        }
        return $this->respondWithSuccess(['products' => $data]);
    }
}
