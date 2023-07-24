<?php

namespace App\Models\Order;

use App\Models\System\Showroom;
use App\Models\User\User;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends BaseModel
{
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'delivery_option_id',
        'user_address_id',
        'voucher_id',
        'transaction_id',
        'order_key',
        'discount_rate',
        'shipping_amount',
        'subtotal_price',
        'total_price',
        'order_note',
        'status',
        'created_at',
        'updated_at'
    ];

    public function showRooms()
    {
        return $this->belongsTo(ShowRoom::class, 'showroom_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product');
    }

    public function productColor()
    {
        return $this->belongsTo('App\Models\Product\ProductColor');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\System\PaymentMethod');
    }

    public function deliveryOption()
    {
        return $this->belongsTo('App\Models\System\DeliveryOption');
    }

    public function userAddress()
    {
        return $this->belongsTo('App\Models\User\UserAddress');
    }

    public function orderStatus()
    {
        return $this->hasMany('App\Models\Order\OrderStatus');
    }

    public function PaymentDetails()
    {
        return $this->hasMany('App\Models\PaymentDetails');
    }

    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetails');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class,'voucher_id','id');
    }

}

