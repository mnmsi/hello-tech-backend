<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
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

    public function calculatePrice(Request $request)
    {
       $product_feature_id = $request->feature_value_id;
       if(isset( $request->feature_value_id)){
           foreach ($product_feature_id as $key => $value){
               $product_feature_id[$key] = (int)$value;
           }
       }
        $product = Product::with(['productFeatureValues','colors'])->where('id',$request->product_id)->first();
       $price =  $this->calculateDiscountPrice($product->price,$product->discount_rate) +  $product->productFeatureValues->whereIn('id',$product_feature_id)->sum('price') + $product->colors->whereIn('id',$request->color_id)->sum('price');
       return $this->respondWithSuccessWithData($price * $request->quantity ?? 1);

    }

    public function getProductByBrand($slug)
    {
        return $this->respondWithSuccessWithData(
            ProductResource::collection($this->getProductByBrandSlug($slug))
        );
    }

//    new arrivals
    public function newArrivals()
    {
        return $this->respondWithSuccessWithData(
            ProductResource::collection($this->getNewArrivals())
        );
    }

    public function featuredNewArrivals()
    {
        return $this->respondWithSuccessWithData(
            ProductResource::collection($this->getFeaturedNewArrivals())
        );
    }
}
