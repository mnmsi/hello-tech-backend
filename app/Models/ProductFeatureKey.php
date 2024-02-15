<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureKey extends BaseModel
{
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
        return $this->hasMany(ProductFeatureValue::class,'product_feature_key_id','id');
    }

    public function featureValues()
    {
        return $this->hasMany(ProductFeatureValue::class,'product_feature_key_id','id');
    }

    public function getValueListAttribute(): array
    {
        if (isset($this->attributes['id'])) {
            $list = [];
            $value = ProductFeatureValue::where('product_feature_key_id', $this->attributes['id'])->get();
            foreach ($value as $l) {
                $list[] = [
                    "layout" => "value",
                    "key" => $l->id,
                    "attributes" => [
                        "value_id" => $l->id,
                        "value_name" => $l->value,
                        "value_price" => $l->price,
                        "value_stock" => $l->stock,
                    ]
                ];
            }
            return $list;
        } else {
            return [];
        }
    }
}
