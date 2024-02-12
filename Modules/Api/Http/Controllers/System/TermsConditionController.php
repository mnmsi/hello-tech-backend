<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\TermsCondition;
use Illuminate\Support\Facades\Cache;

class TermsConditionController extends Controller
{
    public function terms()
    {
        // Cache the response forever
        $terms = Cache::rememberForever('terms_conditions', function () {
            return TermsCondition::first();
        });

        return response()->json([
            'status' => 200,
            'data'   => $terms,
        ]);
    }
}
