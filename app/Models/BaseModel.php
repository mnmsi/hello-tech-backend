<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            Cache::forget($model->getTable());
        });

        static::deleting(function ($model) {
            Cache::forget($model->getTable());
        });
    }
}
