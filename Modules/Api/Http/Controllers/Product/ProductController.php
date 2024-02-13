<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        // Cache the data for 2 minutes
        $data = Cache::remember(json_encode($filterData), 2 * 60, function () use ($filterData) {
            return new ProductCollection($this->getProductsQuery($filterData));
        });

        return $this->respondWithSuccessWithData($data);
    }

    public function details($name)
    {
        $product = Cache::rememberForever('products.' . $name, function () use ($name) {
            return new ProductDetailsResource($this->getProductDetailsBySlug($name));
        });

        return $this->respondWithSuccessWithData($product);
    }

    public function getProductDataById($id)
    {
        return $this->respondWithSuccessWithData(
            new ProductDataResource($this->productDataById($id))
        );
    }

    public function relatedProduct()
    {
//        cache this route for two minutes
        if (Cache::has('related_products')) {
            $product = Cache::get('related_products');
            return $this->respondWithSuccessWithData(
                ProductResource::collection($product)
            );
        } else {
            $product = $this->getRelatedProduct();
            Cache::put('related_products', $product, 120);
            return $this->respondWithSuccessWithData(
                ProductResource::collection($product)
            );
        }
    }

    public function calculatePrice(Request $request)
    {
        $product_feature_id = $request->feature_value_id;
        if (isset($request->feature_value_id)) {
            foreach ($product_feature_id as $key => $value) {
                $product_feature_id[$key] = (int)$value;
            }
        }
        $product        = Product::with(['productFeatureValues', 'colors'])->where('id', $request->product_id)->first();
        $price          = $product->price + $product->productFeatureValues->whereIn('id', $product_feature_id)->sum('price') + $product->colors->whereIn('id', $request->color_id)->sum('price');
        $discount_price = $this->calculateDiscountPrice($product->price, $product->discount_rate ?? 0) + $product->productFeatureValues->whereIn('id', $product_feature_id)->sum('price') + $product->colors->whereIn('id', $request->color_id)->sum('price');
        //        also return discount price after calculation
        return $this->respondWithSuccessWithData([
            'price'          => $price,
            'discount_price' => $discount_price,
        ]);
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
        $data = $this->getFeaturedNewArrivals();
        return $this->respondWithSuccessWithData(
            ProductResource::collection($this->getFeaturedNewArrivals())
        );
    }

    public function searchSuggestions($name)
    {
        return $this->respondWithSuccessWithData(
            ProductResource::collection($this->getSearchSuggestions($name))
        );
    }
}
