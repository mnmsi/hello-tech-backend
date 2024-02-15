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

        static::creating(function ($model) {
            if (!in_array($model->getTable(), self::$ignoreTables)) {
                if (in_array($model->getTable(), self::$productTables)) {
                    $model->checkProductTables($model);
                }
            }
        });

        static::updating(function ($model) {

            if (!in_array($model->getTable(), self::$ignoreTables)) {

                // Forget the cache for the updated model
                Cache::forget($model->getTable());

                if (in_array($model->getTable(), self::$productTables)) {
                    $model->checkProductTables($model);
                } elseif ($model->getTable() == 'banners') {
                    $model->delKeys('banners.*');
                } else {
                    // clear dependency cache for address
                    $model->clearAddressDependencyCache($model->getTable());
                }
            }
        });

        static::deleting(function ($model) {

            if (!in_array($model->getTable(), self::$ignoreTables)) {

                if (in_array($model->getTable(), self::$productTables)) {
                    $model->checkProductTables($model);
                } elseif ($model->getTable() == 'banners') {
                    $model->delKeys('banners.*');
                } else {
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
            $this->delKeys('*.cities*');
            $this->delKeys('*.areas*');
        }
    }

    private function clearProductCache($id): void
    {
        $product = Product::find($id);
        if ($product) {
            Cache::forget('products.' . $product->slug);
        }
    }

    private function checkProductTables($mod): void
    {
        if ($mod->getTable() == 'products') {
            $mod->clearProductCache($mod->id);
        } elseif ($mod->getTable() == 'product_feature_values') {
            $prodKey = ProductFeatureKey::find($mod->product_feature_key_id);
            if ($prodKey) {
                $mod->clearProductCache($prodKey->product_id);
            }
        } elseif ($mod->getTable() == 'product_meta_keys') {
            $prodKey = ProductMetaValue::where('product_meta_key_id', $mod->id);
            if ($prodKey) {
                $mod->clearProductCache($prodKey->product_id);
            }
        } else {
            $mod->clearProductCache($mod->product_id);
        }
    }

    private function delKeys($pattern): void
    {
        $redis = Redis::connection('cache');
        $keys = $redis->keys($pattern);

        foreach ($keys as $key) {
            $redis->del($key);
        }
    }
}
