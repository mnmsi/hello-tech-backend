<?php

namespace App\Models\System;

use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Banner extends BaseModel
{
    protected $fillable = [
        'page',
        'type',
        'show_on',
        'category_id',
        'image_url',
        'home_images',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getHomeImageAttribute(): array
    {
        if (isset($this->attributes['id']) && !empty($this->attributes['home_images'])) {
            $list = [];
            $banner_list = json_decode($this->attributes['home_images'], true);
            foreach ($banner_list as $l) {
                $list[] = [
                    "layout" => "video",
                    "key" => rand(111111, 999999),
                    "attributes" => [
                        "home_image" => $l["image"],
                        "image_url" => $l["url"],
                    ]
                ];
            }
            return $list;
        } else {
            return [];
        }
    }
}
