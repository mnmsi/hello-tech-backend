<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use App\Models\ProductData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Cart extends BaseModel
{
    protected $fillable = [
        'user_id',
        'product_id',
        'product_color_id',
        'product_data',
        'quantity',
        'status',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productColor()
    {
        return $this->belongsTo('App\Models\Product\ProductColor');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }




}

