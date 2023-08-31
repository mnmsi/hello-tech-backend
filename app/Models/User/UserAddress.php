<?php

namespace App\Models\User;

use App\Models\System\Area;
use App\Models\System\City;
use App\Models\System\Division;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class UserAddress extends BaseModel
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'phone',
        'address_line',
        'division_id',
        'city_id',
        'area_id',
        'is_default',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function testDivision()
    {
        return $this->belongsToMany(Division::class, 'division_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

//    if user has only one address then set it default
    public static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            if ($address->user->addresses->count() == 0) {
                $address->is_default = true;
            }
        });
    }
}
