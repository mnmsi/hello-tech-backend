<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMetaKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'key',
    ];

    public function productMetaValues()
    {
        return $this->hasMany(ProductMetaValue::class, 'product_meta_key_id');
    }
}
