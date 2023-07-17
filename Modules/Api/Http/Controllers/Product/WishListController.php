<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Api\Http\Requests\Product\WishListRequest;
use Modules\Api\Http\Resources\Cart\CartResource;
use Modules\Api\Http\Resources\Product\BikeResource;
use Modules\Api\Http\Resources\WishList\WishListResource;
use Modules\Api\Http\Traits\Product\WIshListTrait;

class WishListController extends Controller
{
    use WIshListTrait;

    public function store(WishListRequest $request)
    {
        $this->wishListStore($request);
        return $this->respondWithSuccess([
            'message' => 'Success'
        ]);
    }

    public function list()
    {
        return $this->respondWithSuccessWithData(
            WishListResource::collection($this->wishListList()),
        );
    }

    public function delete($request)
    {
        $this->deleteWishList($request);
        return $this->respondWithSuccess([
            'message' => 'Success'
        ]);
    }
}
