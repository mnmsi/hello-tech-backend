<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class BaseModel extends Model
{
    private static $productTables = [
        'products',
        'product_colors',
        'product_data',
        'product_feature_keys',
        'product_feature_values',
        'product_media',
        'product_meta_keys',
        'product_meta_values',
        'product_reviews',
        'product_specifications',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model)  {

            if (in_array($model->getTable(), self::$productTables)) {
                $product = $model->getProductDetailsById($model->product_id);
                Cache::forget('products.' . $product->slug);
            } else {
                // Forget the cache for the updated model
                Cache::forget($model->getTable());

                // clear dependency cache for address
                $model->clearAddressDependencyCache($model->getTable());
            }
        });

        static::deleting(function ($model) {
            if ($model->getTable() == 'products') {
                Cache::forget('products.' . $model->slug);
            } else {
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

    private function getProductDetailsById($id)
    {
        return Product::find($id);
    }
}
