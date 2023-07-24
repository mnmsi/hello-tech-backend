<?php

namespace App\Models\Product;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class ProductReview extends BaseModel
{
    protected $fillable = [
        'user_id',
        'product_id',
        'title',
        'review',
        'rate',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
