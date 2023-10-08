<?php

namespace App\Models;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tran_id',
        'val_id',
        'amount',
        'customer_name',
        'customer_email',
        'customer_phone',
        'currency',
        'pay_time',
        'bank_txn',
        'card_type',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
