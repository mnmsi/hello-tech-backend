<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\Banner;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Http\Resources\System\BannerResource;

class BannerController extends Controller
{
    public function banners()
    {
        // Cache banners forever
        $data = Cache::rememberForever('banners', function () {
            // Get all active banners
            $banners = Banner::where('is_active', 1)
                ->orderByRaw('ISNULL(order_no), order_no ASC')
                ->get();

            return BannerResource::collection($banners);
        });


        // Return response with banners
        return $this->respondWithSuccessWithData($data);
    }


    public function homeSlider()
    {
        $data = Cache::rememberForever('banners.home_slider', function () {
            // Get all active banners
            $banner = Banner::where('is_active', true)
                ->where('page', 'home-slider')
                ->orderByRaw('ISNULL(order_no), order_no ASC')
                ->get();

            return BannerResource::collection($banner);
        });

        return $this->respondWithSuccessWithData($data);
    }

    public function getBannerByCategory($id)
    {
        try {
            $data = Cache::rememberForever('banners.category_' . $id, function () use ($id) {
                // Get all active banners
                $banner = Banner::where('category_id', $id)
                    ->where('is_active', 1)
                    ->firstOrFail();

                return new BannerResource($banner);
            });

            return $this->respondWithSuccessWithData($data);
        }
        catch (\Exception $e) {
            return $this->respondError('Banner not found');
        }
    }

    public function getBannerByProduct($id)
    {
        try {
            $data = Cache::rememberForever('banners.product_' . $id, function () use ($id) {
                // Get all active banners
                $banner = Banner::where('product_id', $id)
                    ->where('is_active', 1)
                    ->where('show_on', 'all')
                    ->where('page', 'home')
                    ->firstOrFail();

                return new BannerResource($banner);
            });


            return $this->respondWithSuccessWithData($data);
        }
        catch (\Exception $e) {
            return $this->respondError('Banner not found');
        }
        // Return response with banner
    }
}
