<?php

namespace App\Models;

use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestOrderDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'guest_order_id',
        'product_id',
        'product_color_id',
        'feature',
        'price',
        'quantity',
        'discount_rate',
        'subtotal_price',
    ];

    public function order()
    {
        return $this->belongsTo(GuestOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productColor()
    {
        return $this->belongsTo(ProductColor::class);
    }
}
