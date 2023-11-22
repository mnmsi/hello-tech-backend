<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class HomePageSection extends Model
{
    protected $casts = [
        'product_list' => FlexibleCast::class
    ];

    public function homePageSection(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HpsProduct::class, 'hps_section_id', 'id');
    }

    public function getProductListAttribute(): array
    {
        if (isset($this->attributes['id'])) {
            $list = [];
            $product = HpsProduct::where('hps_section_id', $this->attributes['id'])->get();
            foreach ($product as $l) {
                $list[] = [
                    "layout" => "wysiwyg",
                    "key" => substr(uniqid(rand()), 0, 12),
                    "attributes" => [
                        "product" => $l->product_id,
                        "order" => $l->order
                    ]
                ];
            }
            return $list;
        } else {
            return [];
        }
    }
}
