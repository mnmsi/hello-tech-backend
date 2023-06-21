<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'product_image',
        'product_quantity',
        'name',
        'email',
        'phone',
        'address',
    ];
}
