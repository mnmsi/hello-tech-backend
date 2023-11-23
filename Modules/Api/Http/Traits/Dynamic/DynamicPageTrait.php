<?php

namespace Modules\Api\Http\Traits\Dynamic;

use App\Models\Dynamic\DynamicPage;
use App\Models\Dynamic\PromotionalProduct;
use Modules\Api\Http\Resources\Product\ProductResource;

trait DynamicPageTrait
{
    public function checkPageSlug($slug)
    {
        return DynamicPage::where('slug', $slug)->first();
    }

    public function getPageBrandProduct($id)
    {
        return DynamicPage::with('pageBrand')->find($id);
    }

    public function getAllPromotionalProduct()
    {
        try {
            $result = PromotionalProduct::select('id', 'title', 'product_list')
                ->where("status", 1)
                ->get();
            $final = [];
            foreach ($result as $p) {
                if (!empty($p["product_list"])) {
                    $products = collect(json_decode($p["product_list"], true))->sortBy("order")->pluck("product")->toArray();
                    $product_list = \App\Models\Product\Product::whereIn("id", $products)->get();
                    $final[] = [
                        "id" => $p["id"],
                        "title" => $p["title"],
                        "all_products" => ProductResource::collection($product_list),
                    ];
                }
            }
            return $final;
        } catch (\Exception $e) {
            return [];
        }
//        return PromotionalProduct::select('id', 'title', 'product_list')->where("status", 1)->get();
    }
}
