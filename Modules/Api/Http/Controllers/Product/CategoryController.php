<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
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
        $data = Cache::rememberForever('categories', function () {
            return CategoryResource::collection($this->getCategories());
        });

        return $this->respondWithSuccessWithData($data);

    }

    /**
     * @return JsonResponse
     */
    public function popularCategories()
    {
        $data = Cache::rememberForever('categories.popular', function () {
            return CategoryResource::collection($this->getPopularCategories());
        });

        return $this->respondWithSuccessWithData($data);

    }

    public function subCategories(){

        $data = Cache::rememberForever('subcategories', function () {
            return CategoryResource::collection($this->getSubCategories());
        });
        return $this->respondWithSuccessWithData($data);
    }
}
