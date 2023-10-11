<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Api\Http\Resources\Product\CategoryResource;
use Modules\Api\Http\Traits\Product\CategoryTrait;

class CategoryController extends Controller
{
    use CategoryTrait;

    /**
     * @return JsonResponse
     */
    public function categories()
    {
        return $this->respondWithSuccessWithData(
            CategoryResource::collection($this->getCategories())
        );
    }

    /**
     * @return JsonResponse
     */
    public function popularCategories()
    {
        return $this->respondWithSuccessWithData(
            CategoryResource::collection($this->getPopularCategories())
        );
    }

    public function subCategories(){
        return $this->respondWithSuccessWithData(
            CategoryResource::collection($this->getCategoryWithSubCategory())
        );
    }
}
