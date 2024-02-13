<?php

namespace Modules\Api\Http\Traits\Product;

use App\Models\Product\Category;
use App\Models\ProductMetaKey;

trait ProductMetaTrait
{
    public function getMetaByCategory($id)
    {
        if ($id == 'gadgets') {
            return ProductMetaKey::with('productMetaValues')->inRandomOrder()->limit(3)->get();
        }
        else {
            $slug = Category::where('slug', $id)->first()->id;
            if ($slug) {
                return ProductMetaKey::with('productMetaValues')->where('category_id', $slug)->get();
            }
            else {
                return [];
            }
        }
    }
}
