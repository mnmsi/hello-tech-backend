<?php

namespace App\Models\System;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HpsProduct extends Model
{
    protected $fillable = [
        'hps_section_id',
        'product_id',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function homeSection(): BelongsTo
    {
        return $this->belongsTo(HomePageSection::class,'hps_section_id','id');
    }
}
