<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureValue extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_feature_key_id',
        'value',
    ];

    public function productFeatureKey()
    {
        return $this->belongsTo(ProductFeatureKey::class);
    }

    public function productDatas()
    {
        return $this->hasMany(ProductData::class);
    }
}
