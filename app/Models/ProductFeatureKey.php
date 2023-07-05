<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureKey extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'key',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productFeatureValues()
    {
        return $this->hasMany(ProductFeatureValue::class);
    }
}
