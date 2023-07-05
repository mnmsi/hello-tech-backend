<?php

namespace App\Models;

use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductData extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_feature_value_id',
        'product_color_id',
        'key',
        'value',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productFeatureValues()
    {
        return $this->belongsTo(ProductFeatureValue::class);
    }

    public function productColor()
    {
        return $this->belongsTo(ProductColor::class);
    }
}
