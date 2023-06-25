<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMetaValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_meta_key_id',
        'value',
    ];

    public function productMetaKey()
    {
        return $this->belongsTo(ProductMetaKey::class, 'product_meta_key_id');
    }
}
