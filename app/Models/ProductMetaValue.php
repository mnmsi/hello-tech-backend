<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMetaValue extends Model
{
    protected $fillable = [
        'product_meta_key_id',
        'value',
    ];

    public function productMetaKey()
    {
        return $this->belongsTo(ProductMetaKey::class, 'product_meta_key_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_meta_values', 'product_meta_value_id', 'product_id');
    }
}
