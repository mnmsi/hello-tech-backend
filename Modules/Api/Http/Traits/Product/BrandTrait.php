<?php

namespace Modules\Api\Http\Traits\Product;

use App\Models\BaseModel;
use App\Models\Product\Brand;
use App\Models\Product\Category;
use LaravelIdea\Helper\App\Models\_IH_BaseModel_C;
use LaravelIdea\Helper\App\Models\Product\_IH_Brand_C;

trait BrandTrait
{
    /**
     * @return mixed
     */
    public function brands()
    {
        return Brand::where('is_active', 1)->orderBy('id', 'desc')
            ->paginate(request('per_page', 9));
    }

    /**
     * @return mixed
     */
    public function getPopularBrands()
    {
        return Brand::where('is_active', 1)
            ->where('is_popular', 1)
            ->limit(14)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * @param $id
     * @return BaseModel[]|Brand[]|_IH_BaseModel_C|_IH_Brand_C
     */
    public function getCategoryBrands($slug)
    {
        if ($slug == 'gadgets') {
            return Brand::where('is_active', 1)->inRandomOrder()->get();
        }
        if ($slug == 'all') {
            return Brand::where('is_active', 1)
                ->orderBy('id', 'asc')
                ->get();
        } else {
           return Brand::where('is_active', 1)->where('category_id', $slug)->orWhere('sub_category_id', $slug)->orderBy('id', 'asc')->get();

        }

    }


}
