<?php

namespace App\Models\Dynamic;

use App\Models\Product\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicPageBrand extends Model
{
    protected $table = 'dynamic_page_brand';

    protected $fillable = [
        'dynamic_page_id',
        'brand_id',
        'product_count',
    ];

    public function dynamicPage()
    {
        return $this->belongsTo(DynamicPage::class,'dynamic_page_id','id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
