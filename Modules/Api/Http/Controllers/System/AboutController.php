<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    public function index()
    {
        // Cache the response forever
        $about = Cache::rememberForever('abouts', function () {
            return About::first();
        });

        return response()->json([
            'status' => 'success',
            'data'   => $about,
        ]);
    }
}
