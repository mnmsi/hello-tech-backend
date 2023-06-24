<?php

namespace App\Models\Product;

use App\Models\BaseModel;
use App\Models\System\Banner;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;


class Category extends BaseModel
{
    protected $fillable = [
        'name',
        'image_url',
        'icon',
        'slug',
        'is_popular',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function banner()
    {
        return $this->hasOne(Banner::class);
    }
}
