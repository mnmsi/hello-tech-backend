<?php

namespace App\Models\Dynamic;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Api\Http\Resources\Product\ProductResource;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class PromotionalProduct extends Model
{
    protected $table = 'promotional_products';

    protected $fillable = [
        'title',
        'product_list',
        'status',
    ];

    protected $casts = [
        'new_product_list' => FlexibleCast::class
    ];

    public function getNewProductListAttribute(): array
    {
        if (isset($this->attributes['product_list'])) {
            $list = [];
            $product = json_decode($this->attributes['product_list'], true);
            foreach ($product as $l) {
                $list[] = [
                    "layout" => "wysiwyg",
                    "key" => substr(uniqid(rand()), 0, 12),
                    "attributes" => [
                        "product" => $l["product"],
                        "order" => $l["order"]
                    ]
                ];
            }
            return $list;
        } else {
            return [];
        }
    }
}
