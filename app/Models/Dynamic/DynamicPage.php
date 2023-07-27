<?php

namespace App\Models\Dynamic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DynamicPage extends Model
{
    protected $table= 'dynamic_page';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    protected $fillable = [
        'title',
        'slug',
        'all_brand',
        'product_count',
        'description',
        'status',
    ];

    public function pageBrand()
    {
        return $this->hasMany(DynamicPageBrand::class);
    }

    //    list
    public function getBrandListAttribute(): array
    {
        if (isset($this->attributes['id'])) {
            $list = [];
            $product = DynamicPageBrand::where('dynamic_page_id', $this->attributes['id'])->get();
            foreach ($product as $l) {
                $list[] = [
                    "layout" => "brand",
                    "key" => $l->id,
                    "attributes" => [
                        "brand_id" => $l->id,
                        "brand_select" => $l->brand_id,
                        "brand_product_count" => $l->product_count,
                    ]
                ];
            }
            return $list;
        } else {
            return [];
        }
    }
}
