<?php

namespace Modules\Api\Http\Traits\Product;

use App\Models\Product\Brand;
use App\Models\Product\Category;

trait CategoryTrait
{
    /**
     * @return mixed
     */
    public function getCategories()
    {
        return Category::where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getPopularCategories()
    {
        return Category::where('is_active', 1)
            ->where('is_popular', 1)
            ->with('products')
            ->orderBy('name', 'asc')
            ->get();
    }
}
