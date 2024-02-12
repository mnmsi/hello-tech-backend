<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\PrivacyPolicy;
use Illuminate\Support\Facades\Cache;

class PrivacyPolicyController extends Controller
{
    public function privacyPolicy()
    {
        $privacy = Cache::rememberForever('privacy_policy', function () {
            return PrivacyPolicy::first();
        });

        return response()->json([
            'status' => 200,
            'data'   => $privacy,
        ]);
    }
}
