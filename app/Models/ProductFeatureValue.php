<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureValue extends Model
{
    protected $fillable = [
        'product_feature_key_id',
        'value',
        'price',
    ];

    public function productFeatureKey()
    {
        return $this->belongsTo(ProductFeatureKey::class);
    }

//    public function productDatas()
//    {
//        return $this->hasMany(ProductData::class);
//    }
}
