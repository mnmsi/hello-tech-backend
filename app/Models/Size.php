<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'product_id', // 'product_id' is a foreign key to 'id' of 'products' table
        'name',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
