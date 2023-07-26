<?php

namespace App\Models\Product;

use App\Models\Sell\SellBike;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Brand extends BaseModel
{

    protected $fillable = [
        'name',
        'image_url',
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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
