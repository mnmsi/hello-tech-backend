<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureValue extends BaseModel
{
    protected $table = "product_feature_values";
    protected $fillable = [
        'product_feature_key_id',
        'value',
        'price',
        'stock',
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
