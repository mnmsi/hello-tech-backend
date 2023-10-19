<?php

namespace App\Models;

use App\Models\Product\Brand;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'slug',
        'is_popular',
        'is_active',
        'name',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    protected $casts = [
        'is_popular' => 'boolean',
        'is_active' => 'boolean'
    ];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->slug = Str::slug($post->name);
        });

        static::updating(function ($post) {
            $post->slug = Str::slug($post->name);
        });
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
}
