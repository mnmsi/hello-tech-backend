<?php

namespace App\Models;

use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMetaKey extends BaseModel
{
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'key',
    ];

    public function productMetaValues()
    {
        return $this->hasMany(ProductMetaValue::class, 'product_meta_key_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function getMetaListAttribute(): array
    {
        if (isset($this->attributes['id'])) {
            $list = [];
            $value = ProductMetaValue::where('product_meta_key_id', $this->attributes['id'])->get();
            foreach ($value as $l) {
                $list[] = [
                    "layout" => "meta",
                    "key" => $l->id,
                    "attributes" => [
                        "meta_id" => $l->id,
                        "meta_name" => $l->value,
                        "meta_product" => $l->product_id,
                    ]
                ];
            }
            return $list;
        } else {
            return [];
        }
    }
}
