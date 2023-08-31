<?php

namespace App\Models\Dynamic;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Api\Http\Resources\Product\ProductResource;

class PromotionalProduct extends Model
{
    protected $table = 'promotional_products';

    protected $appends = [
        'all_product'
    ];

    public function getAllProductAttribute(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ProductResource::collection(Product::whereIn('id', json_decode($this->attributes['product_list']))->get());
    }
}
