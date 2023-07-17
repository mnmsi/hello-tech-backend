<?php

namespace Modules\Api\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Api\Http\Requests\Order\AddCartRequest;
use Modules\Api\Http\Resources\Cart\CartResource;
use Modules\Api\Http\Traits\Order\CartTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;


class CartController extends Controller
{
    use CartTrait, ProductTrait;

    /**
     * @return JsonResponse
     */
    public function carts()
    {
        $carts = $this->getCartedData();
        return $this->respondWithSuccess([
            'data' => CartResource::collection($carts),
        ]);
    }

    /**
     * @param AddCartRequest $request
     * @return JsonResponse
     */
    public function store(AddCartRequest $request): JsonResponse
    {
        $data = $this->addProductToCart($request); // Get carted data
        if ($data) {
            return $this->respondWithSuccess([
                'message' => 'Product added to cart successfully',
            ]);
        } else {
            return $this->respondError('Product already added to cart');
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function removeCart($id)
    {
        $cart = $this->removeProductFromCart($id);
        if ($cart) {
            return $this->respondWithSuccess([
                'message' => 'Product removed from cart successfully',
            ]);
        } else {
            return $this->respondError('Product not found in cart');
        }
    }

    /**
     * @return JsonResponse
     */

    public function updateCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:carts,id',
        ]);
        $cart = $this->updateCartProduct($request);
        if ($cart) {
            return $this->respondWithSuccess([
                'message' => 'Product updated in cart successfully',
            ]);
        } else {
            return $this->respondError('Product not found in cart');
        }
    }

    /**
     * @return JsonResponse
     */

    public function getSelectedProduct()
    {
        $product = $this->getSelectedCartProduct();
        return $this->respondWithSuccess([
            'data' => CartResource::collection($product),
            'total_price' => $this->getTotalPrice(),
        ]);
    }
}
