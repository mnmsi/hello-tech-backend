<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {

            if ($model->getTable() == 'products') {
                Cache::forget('products.' . $model->slug);
            }
            else {
                // Forget the cache for the updated model
                Cache::forget($model->getTable());

                // clear dependency cache for address
                $this->clearAddressDependencyCache($model->getTable());
            }
        });

        static::deleting(function ($model) {
            if ($model->getTable() == 'products') {
                Cache::forget('products.' . $model->slug);
            }
            else {
                // Forget the cache for the deleted model
                Cache::forget($model->getTable());
            }
        });
    }

    private function clearAddressDependencyCache($table): void
    {
        $dependencyAddDB = [
            'divisions',
            'cities',
            'areas',
        ];

        if (in_array($table, $dependencyAddDB)) {
            Redis::del('*.cities');
            Redis::del('*.areas');
        }
    }
}
