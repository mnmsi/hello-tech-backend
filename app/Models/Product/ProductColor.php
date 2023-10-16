<?php

namespace App\Models\Product;

use App\Models\GuestCart;
use App\Models\ProductData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class ProductColor extends BaseModel
{
    protected $fillable = [
        'product_id',
        'name',
        'color_code',
        'stock',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productsData()
    {
        return $this->hasMany(ProductData::class);
    }

    public function guestCart()
    {
        return $this->hasMany(GuestCart::class);
    }
}
