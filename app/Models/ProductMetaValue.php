<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMetaValue extends Model
{
    protected $fillable = [
        'product_meta_key_id',
        'product_id',
        'value',
    ];

    public function productMetaKey()
    {
        return $this->belongsTo(ProductMetaKey::class, 'product_meta_key_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
