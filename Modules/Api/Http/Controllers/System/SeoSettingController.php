<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\SeoSetting;
use App\Models\System\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SeoSettingController extends Controller
{
    public function seoSettings(Request $request)
    {
        $data = SeoSetting::select('page_description', 'page_keywords', 'page_url', 'page_title')
            ->where('page_url', $request->page_url)
            ->first();

        // Cache the response forever
        $data = Cache::rememberForever('seo_settings', function () use ($data) {
            return $data;
        });

        return $this->respondWithSuccessWithData($data);
    }
}
