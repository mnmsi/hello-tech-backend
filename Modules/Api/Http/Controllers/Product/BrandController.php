<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
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
        return $this->respondWithSuccessWithData(
            new BrandCollection($this->brands())
        );
    }

    /**
     * @return JsonResponse
     */
    public function popularBrands()
    {
        return $this->respondWithSuccessWithData(
            BrandResource::collection($this->getPopularBrands())
        );
    }
    /**
     * @param $id
     * @return JsonResponse
     */
    public function categoryBrands($id)
    {
        return $this->respondWithSuccessWithData(
            BrandResource::collection($this->getCategoryBrands($id))
        );
    }
}
