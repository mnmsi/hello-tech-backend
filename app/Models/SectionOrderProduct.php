<?php

namespace App\Models;

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
}
