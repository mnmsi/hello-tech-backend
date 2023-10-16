<?php

namespace Modules\Api\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\GuestCart;
use App\Models\GuestUser;
use App\Models\Order\Cart;
use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use App\Models\ProductFeatureValue;
use Illuminate\Http\Request;
use Modules\Api\Http\Requests\Order\AddCartRequest;
use Modules\Api\Http\Resources\Cart\CartResource;
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
        $guestUser = GuestUser::where('uuid', $request->guest_user_id)->first();
        $checkCart = GuestCart::where('guest_user_id', $guestUser->id)->where('product_id', $request->product_id)->where('product_color_id', $request->product_color_id)->where('product_data', $request->product_data)->first();
        if ($checkCart) {
            return $this->respondError('Product already added to cart');
        } else {
            $cart = GuestCart::create([
                'guest_user_id' => $guestUser->id,
                'product_id' => $request->product_id,
                'product_color_id' => $request->product_color_id,
                'product_data' => $request->product_data,
                'quantity' => $request->quantity,
            ]);
        }
        if ($cart) {
            return $this->respondWithSuccess(['message' => 'Product added to cart']);
        }
    }


    public function getCartProduct($guest_user_id)
    {
        $guest_user = GuestUser::where('uuid', $guest_user_id)->first();
        $guestCart = GuestCart::wherehas('productColor', function ($q) {
            $q->where('stock', '>', 0);
        })->with(['product'])->where('guest_user_id', $guest_user->id)->get();
        if ($guestCart) {
            return $this->respondWithSuccess([
                'data' => CartResource::collection($guestCart),
            ]);;
        }
    }

    public function getSelectedCartProduct($guest_user_id)
    {

        $guest_user = GuestUser::where('uuid', $guest_user_id)->first();
        $cart = GuestCart::wherehas('productColor', function ($q) {
            $q->where('stock', '>', 0);
        })->where('guest_user_id', $guest_user->id)->with('product')->where('status', '1')->get();
        return $this->respondWithSuccess([
            'data' => CartResource::collection($cart),
        ]);
    }

    public function updateCart(Request $request)
    {
        $guest_user = GuestUser::where('uuid', $request->guest_user_id)->first();
        $cart = GuestCart::where('id', $request->cart_id)->where('guest_user_id', $guest_user->id)->first();
        if ($cart) {
            $cart->update([
                'quantity' => $request->quantity,
                'status' => $request->status,
            ]);
            return $this->respondWithSuccess([
                'message' => 'Product updated in cart successfully',
            ]);
        } else {
            return $this->respondError('Product not found in cart');
        }
    }

    public function removeProductFromCart(Request $request)
    {
        $guest_user = GuestUser::where('uuid', $request->guest_user_id)->first();
        $cart = GuestCart::where('id', $request->cart_id)->where('guest_user_id', $guest_user->id)->first();
        if ($cart) {
            $cart->delete();
            return $this->respondWithSuccess([
                'message' => 'Product removed from cart successfully',
            ]);
        } else {
            return $this->respondError('Product not found in cart');
        }
    }

    public function createGuestUser(Request $request)
    {
        $guestUser = GuestUser::create([
            'uuid' => $request->uuid,
        ]);
        if ($guestUser) {
            return $this->respondWithSuccess(['message' => 'Guest user created successfully']);
        } else {
            return $this->respondError('Something went wrong');
        }
    }
}
