<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    private static array $ignoreTables = [
        'carts',
        'guest_carts',
        'guest_users',
        'orders',
        'order_details',
        'guest_orders',
        'guest_order_details',
        'pre_orders',
        'section_orders',
        'section_order_products',
    ];

    private static array $productTables = [
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

        static::updating(function ($model) {

            if (!in_array($model->getTable(), self::$ignoreTables)) {

                // Forget the cache for the updated model
                Cache::forget($model->getTable());

                if (in_array($model->getTable(), self::$productTables)) {
                    $model->clearProductCache($model->product_id);
                }
                elseif ($model->getTable() == 'banners') {
                    $model->delKeys('*banners.*');
                }
                else {
                    // clear dependency cache for address
                    $model->clearAddressDependencyCache($model->getTable());
                }
            }
        });

        static::deleting(function ($model) {

            if (!in_array($model->getTable(), self::$ignoreTables)) {

                if (in_array($model->getTable(), self::$productTables)) {
                    $model->clearProductCache($model->product_id);
                }
                elseif ($model->getTable() == 'banners') {
                    Redis::del('banners.*');
                }
                else {
                    // Forget the cache for the deleted model
                    Cache::forget($model->getTable());
                }
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

    private function clearProductCache($id): void
    {
        $product = Product::find($id);
        Cache::forget('products.' . $product->slug);
    }

    private function delKeys($pattern): void
    {
        $redis = Redis::connection('cache');
        $keys = $redis->keys($pattern);

        foreach ($keys as $key) {
            $key = Str::replace('laravel_database_', '', $key);
            $redis->del($key);
        }
    }
}
