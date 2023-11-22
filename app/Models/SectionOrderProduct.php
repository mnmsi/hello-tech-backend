<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        "section_order_id",
        "product_id",
        "order",
        "is_active",
    ];

    public function sectionOrder()
    {
        return $this->belongsTo(SectionOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
