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

    public function featuredProduct($categoryId){
        return Product::where('category_id',$categoryId)->where('is_featured',1)->get();
    }
}
