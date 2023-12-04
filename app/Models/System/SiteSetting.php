<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class SiteSetting extends Model
{
    protected $casts = [
        'section_list' => FlexibleCast::class
    ];

    public function getSectionListAttribute(): array
    {
        if (isset($this->attributes['section_order'])) {
            $list = [];
            $product = json_decode($this->attributes['section_order'], true);
            foreach ($product as $l) {
                $list[] = [
                    "layout" => "wysiwyg",
                    "key" => substr(uniqid(rand()), 0, 12),
                    "attributes" => [
                        "section_name" => $l["section_name"],
                        "order_no" => $l["order_no"]
                    ]
                ];
            }
            return $list;
        } else {
            return [];
        }
    }
}
