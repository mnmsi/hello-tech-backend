<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class SectionOrder extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        "section",
        "is_active",
    ];

    protected $casts = [
        'product_list' => FlexibleCast::class
    ];

    public function sectionOrderProducts()
    {
        return $this->hasMany(SectionOrderProduct::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'section_order_products');
    }

    public function getProductListAttribute(): array
    {
        if (isset($this->attributes['id'])) {
            $list = [];
            $product = SectionOrderProduct::where('section_order_id', $this->attributes['id'])->get();
            foreach ($product as $l) {
                $list[] = [
                    "layout" => "wysiwyg",
                    "key" => substr(uniqid(rand()), 0, 12),
                    "attributes" => [
                        "product" => $l->product_id,
                        "order" => $l->order
                    ]
                ];
            }
            return $list;
        } else {
            return [];
        }
    }

}
