<?php

namespace App\Models;

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

    public function guestOrder()
    {
        return $this->belongsTo(GuestOrder::class);
    }
}
