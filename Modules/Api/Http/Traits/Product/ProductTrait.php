<?php

namespace Modules\Api\Http\Traits\Product;

use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\ProductData;

trait ProductTrait
{
    /**
     * @param $price
     * @param $discountRate
     * @return float|int
     */
    public function calculateDiscountPrice($price, $discountRate)
    {
        return round($price - ($price * $discountRate / 100));
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function getProductDetails($productId)
    {
        return Product::find($productId);
    }

    public function featuredProduct($categoryId)
    {
        return Product::wherehas('colors',function ($q){
            $q->where('stock','>',0);
        })->where('category_id', $categoryId)->where('is_featured', 1)->limit(4)->orderBy('order_no')->get();
    }

    public function initializeFilterData($request)
    {
        return [
            'name' => $request->name ?? null,
            'category' => $request->category ?? null,
            'brand' => $request->brand ?? null,
            'is_official' => $request->is_official ?? null,
            'value' => $request->value ?? null,
            'short_by' => $request->short_by ?? null,
        ];
    }

    public function getProductsQuery($params)
    {
        if ($params['category'] == 'gadgets') {
            $params['category'] = null;
        }
        return Product::wherehas('colors',function ($q){
            $q->where('stock','>',0);
        })->where('is_active', 1)
            ->when($params['name'], function ($query) use ($params) {
                $query->where('name', 'like', '%' . $params['name'] . '%');
            })
            ->when($params['category'], function ($query) use ($params) {
//                $query->where('category_id', $id);
                $query->whereHas('category', function ($query) use ($params) {
                    $query->where('slug', $params['category']);
                });
            })
            ->when($params['brand'], function ($query) use ($params) {
                $query->where('brand_id', $params['brand']);
            })
            ->when($params['is_official'], function ($query) use ($params) {
                $query->where('is_official', $params['is_official']);
            })->when($params['value'], function ($query) use ($params) {
                $query->whereHas('metaValue', function ($query) use ($params) {
                    $query->whereIn('id', [$params['value']]);
                });
            })
            ->when($params['short_by'], function ($query) use ($params) {
                $query->orderBy('price', $params['short_by']);
            })->orderBy('order_no')
            ->paginate(9);
    }

    public function getProductDetailsBySlug($slug)
    {
        return Product::where('slug', $slug)->with(['productFeatureKeys' => function ($q) {
            $q->with(['productFeatureValues' => function ($q) {
                $q->where('stock', '>', 0);
            }]);
        }, 'banner', 'colors' => function ($c) {
            $c->where('stock', '>', 0);
        }])->first();
    }

    public function productDataById($id)
    {
        return ProductData::wherehas('colors',function ($q){
            $q->where('stock','>',0);
        })->where('product_feature_value_id', $id)->orWhere('product_color_id', $id)->first();
    }

    public function getRelatedProduct()
    {
        return Product::where('is_active', 1)->whereHas('colors', function ($query) {
            $query->where('stock', '>', 0);
        })->inRandomOrder()->take(4)->get();
    }
    public function getProductByBrandSlug($slug)
    {
        return Product::wherehas('colors',function ($q){
            $q->where('stock','>',0);
        })->where('is_active', 1)
            ->whereHas('brand', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->orderBy('order_no')->get();
    }

    public function getNewArrivals()
    {
        return Product::wherehas('colors',function ($q){
            $q->where('stock','>',0);
        })->where('is_active', 1)
            ->where('is_new_arrival', 1)
            ->orderBy('order_no')
            ->get();

    }

    public function getFeaturedNewArrivals()
    {
        return Product::wherehas('colors',function ($q){
            $q->where('stock','>',0);
        })->where('is_new_arrival', 1)
            ->where('is_featured', 1)
            ->orderBy('order_no')
            ->get();
    }
}
