<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'created_at',
        'updated_at'
    ];

    public function guestCart()
    {
        return $this->hasMany(GuestCart::class);
    }
}
