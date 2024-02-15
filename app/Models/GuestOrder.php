<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestOrder extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'city',
        'division',
        'area',
        'delivery_option',
        'payment_method',
        'address_line',
        'order_note',
        'voucher_code',
        'transaction_id',
        'order_key',
        'discount_rate',
        'shipping_amount',
        'subtotal_price',
        'total_price',
        'status',
    ];

    public function orderItems()
    {
        return $this->hasMany(GuestOrderDetails::class);
    }
}
