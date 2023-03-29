<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Showroom extends BaseModel
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'country_id',
        'division_id',
        'city_id',
        'area_id',
        'postal_code',
        'location_image_url',
        'support_number',
        'is_active',
        'created_at',
        'updated_at'
    ];
}
