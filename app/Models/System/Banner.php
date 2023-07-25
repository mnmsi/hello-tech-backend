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
}
