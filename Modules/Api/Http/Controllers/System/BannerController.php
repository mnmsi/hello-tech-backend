<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\Banner;
use Modules\Api\Http\Resources\System\BannerResource;

class BannerController extends Controller
{
    public function banners()
    {
        // Get all active banners
        $banners = Banner::where('is_active', 1)
            ->orderByRaw('ISNULL(order_no), order_no ASC')
            ->get();

        // Return response with banners
        return $this->respondWithSuccessWithData(BannerResource::collection($banners));
    }

    public function getBannerByCategory($id)
    {
        // Get banner by id
        try {
            $banner = Banner::where('category_id', $id)
                ->where('is_active', 1)
                ->firstOrFail();
            return $this->respondWithSuccessWithData(new BannerResource($banner));
        } catch (\Exception $e) {
            return $this->respondError('Banner not found');
        }
    }

    public function getBannerByProduct($id)
    {
        // Get banner by id
        try {
            $banner = Banner::where('product_id', $id)
                ->where('is_active', 1)
                ->where('show_on', 'all')
                ->where('page','home')
                ->firstOrFail();
            return $this->respondWithSuccessWithData(new BannerResource($banner));
        } catch (\Exception $e) {
            return $this->respondError('Banner not found');
        }
        // Return response with banner
    }
}
