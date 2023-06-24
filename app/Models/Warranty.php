<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    protected $table = 'warranties';

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
