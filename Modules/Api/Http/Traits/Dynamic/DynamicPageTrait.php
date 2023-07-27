<?php

namespace Modules\Api\Http\Traits\Dynamic;

use App\Models\Dynamic\DynamicPage;

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
}
