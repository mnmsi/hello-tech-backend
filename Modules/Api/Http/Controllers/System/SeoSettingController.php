<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\SeoSetting;
use App\Models\System\SiteSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function seoSettings(Request $request)
    {
        $name = $request->name ?? 'default';
        $data = SeoSetting::select('page_title', 'page_description', 'page_keywords')
            ->where('page_title', 'LIKE', '%' . $name . '%')
            ->first();
        return $this->respondWithSuccessWithData($data);
    }
}
