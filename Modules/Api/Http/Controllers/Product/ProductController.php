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
        $featuredProduct = Cache::remember('featured_products.' . $categoryId, config('cache.stores.redis.lifetime'), function () use ($categoryId) {
            return ProductResource::collection($this->featuredProduct($categoryId));
        });

        return $this->respondWithSuccessWithData($featuredProduct);
    }

    public function getProduct(Request $request)
    {
        $filterData = $this->initializeFilterData($request);

        // Cache the data for 2 minutes
        $data = Cache::remember(json_encode($filterData), config('cache.stores.redis.lifetime'), function () use ($filterData) {
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
        $data = Cache::remember('product_data.' . $id, config('cache.stores.redis.lifetime'), function () use ($id) {
            return new ProductDataResource($this->productDataById($id));
        });

        return $this->respondWithSuccessWithData($data);
    }

    public function relatedProduct()
    {
        // Cache the data for 2 minutes
        $product = Cache::remember('related_products', config('cache.stores.redis.lifetime'), function () {
            return ProductResource::collection($this->getRelatedProduct());
        });

        return $this->respondWithSuccessWithData($product);
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
        $data = Cache::remember('brand.products.' . $slug, config('cache.stores.redis.lifetime'), function () use ($slug) {
            return ProductResource::collection($this->getProductByBrandSlug($slug));
        });

        return $this->respondWithSuccessWithData($data);
    }

    //    new arrivals
    public function newArrivals()
    {
        $data = Cache::remember('new_arrivals', config('cache.stores.redis.lifetime'), function () {
            return ProductResource::collection($this->getNewArrivals());
        });

        return $this->respondWithSuccessWithData($data);
    }

    public function featuredNewArrivals()
    {
        $data = Cache::remember('featured_new_arrivals', config('cache.stores.redis.lifetime'), function () {
            return ProductResource::collection($this->getFeaturedNewArrivals());
        });

        return $this->respondWithSuccessWithData($data);
    }

    public function searchSuggestions($name)
    {
        $data = Cache::remember('search_suggestions.' . $name, config('cache.stores.redis.lifetime'), function () use ($name) {
            return ProductResource::collection($this->getSearchSuggestions($name));
        });

        return $this->respondWithSuccessWithData($data);
    }
}
