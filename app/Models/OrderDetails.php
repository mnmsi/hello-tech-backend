<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetails extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_color_id',
        'price',
        'quantity',
        'total',
        'discount_rate',
        'subtotal_price',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function product_color(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }
}
