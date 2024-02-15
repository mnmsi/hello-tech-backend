<?php

namespace App\Models;

use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestCart extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'guest_user_id',
        'product_id',
        'product_color_id',
        'product_data',
        'quantity',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productColor()
    {
        return $this->belongsTo(ProductColor::class);
    }

    public function guestUser()
    {
        return $this->belongsTo(GuestUser::class);
    }


}
