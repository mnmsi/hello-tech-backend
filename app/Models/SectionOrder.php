<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        "section",
        "is_active",
    ];

    public function sectionOrderProducts()
    {
        return $this->hasMany(SectionOrderProduct::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'section_order_products');
    }

}
