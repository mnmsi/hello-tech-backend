<?php

namespace Modules\Api\Http\Traits\Product;

use App\Models\Product\Product;

trait ProductTrait
{
    /**
     * @param $price
     * @param $discountRate
     * @return float|int
     */
    public function calculateDiscountPrice($price, $discountRate)
    {
        return $price - ($price * $discountRate / 100);
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
        return Product::where('category_id', $categoryId)->where('is_featured', 1)->get();
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
//        dd($params);
        return Product::where('is_active', 1)
            ->when($params['name'], function ($query) use ($params) {
                $query->where('name', 'like', '%' . $params['name'] . '%');
            })
            ->when($params['category'], function ($query) use ($params) {
                $query->where('category_id', $params['category']);
            })
            ->when($params['brand'], function ($query) use ($params) {
                $query->where('brand_id', $params['brand']);
            })
            ->when($params['is_official'], function ($query) use ($params) {
                $query->where('is_official', $params['is_official']);
            })->when($params['value'], function ($query) use ($params) {
                $query->whereIn('product_meta_key_id', $params['value']);
            })->paginate(10);

    }
}
