<?php

namespace App\Models\User;

use App\Models\BaseModel;

class PhoneVerification extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'email',
        'phone',
        'otp',
        'expires_at',
    ];
}
