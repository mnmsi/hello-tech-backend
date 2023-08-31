<?php

namespace Modules\Api\Http\Traits\Dynamic;

use App\Models\Dynamic\DynamicPage;
use App\Models\Dynamic\PromotionalProduct;

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
        return PromotionalProduct::select('id','title','product_list')->where("status", 1)->get();
    }
}
