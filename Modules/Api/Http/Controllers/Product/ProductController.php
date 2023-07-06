<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Api\Http\Resources\Product\ProductCollection;
use Modules\Api\Http\Resources\Product\ProductDataResource;
use Modules\Api\Http\Resources\Product\ProductDetailsResource;
use Modules\Api\Http\Resources\Product\ProductResource;
use Modules\Api\Http\Traits\Product\ProductCountTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;

class ProductController extends Controller
{
    use ProductCountTrait;
    use ProductTrait;

    /**
     * @return JsonResponse
     */
    public function totalProductType()
    {
        return $this->respondWithSuccessWithData([
            'total_new_bikes' => $this->totalNewBikes(),
            'total_used_bikes' => $this->totalUsedBikes(),
            'total_accessories' => $this->totalAccessories(),
            'total_shops' => $this->totalShops(),
        ]);
    }

    public function getFeaturedProduct($categoryId)
    {
        return $this->respondWithSuccessWithData(
            ProductResource::collection($this->featuredProduct($categoryId))
        );
    }

    public function getProduct(Request $request)
    {

        $filterData = $this->initializeFilterData($request);
        return $this->respondWithSuccessWithData(
            new ProductCollection($this->getProductsQuery($filterData))
        );
    }

    public function details($name)
    {
//        $data = $this->getProductDetailsBySlug($name);
        return $this->respondWithSuccessWithData(
            new ProductDetailsResource($this->getProductDetailsBySlug($name))
        );
    }

    public function getProductDataById($id)
    {
       return $this->respondWithSuccessWithData(
                new ProductDataResource($this->productDataById($id))
            );
    }

    public function relatedProduct()
    {
        return $this->respondWithSuccessWithData(
            ProductResource::collection($this->getRelatedProduct())
        );
    }
}
