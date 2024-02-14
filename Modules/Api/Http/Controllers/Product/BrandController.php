<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Http\Resources\Product\BrandCollection;
use Modules\Api\Http\Resources\Product\BrandResource;
use Modules\Api\Http\Traits\Product\BrandTrait;

class BrandController extends Controller
{
    use BrandTrait;

    /**
     * @return JsonResponse
     */
    public function index()
    {
//        cache the response

        $data = Cache::rememberForever('brands', function () {
            return new BrandCollection($this->brands());
        });

        return $this->respondWithSuccessWithData($data);
    }

    /**
     * @return JsonResponse
     */
    public function popularBrands()
    {
        $data = Cache::rememberForever('brands.popular', function () {
            return BrandResource::collection($this->getPopularBrands());
        });

        return $this->respondWithSuccessWithData($data);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function categoryBrands($slug)
    {
        $data = Cache::rememberForever('brands.category.' . $slug, function () use ($slug) {
            return BrandResource::collection($this->getCategoryBrands($slug));
        });

        return $this->respondWithSuccessWithData($data);
    }
}
