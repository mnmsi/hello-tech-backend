<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductColor;
use App\Models\System\Showroom;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Http\Resources\System\ShowroomResource;

class ColorController extends Controller
{
    public function colors()
    {
        // check if the response is cached
        if (Cache::has('product_colors')) {
            return $this->respondWithSuccessWithData(Cache::get('product_colors'));
        }

        // Get Unique Colors from ProductColor
        $colors = ProductColor::select('name')
                              ->distinct()
                              ->pluck('name');

        // cache the response forever
        Cache::forever('product_colors', $colors);

        // Return response with colors
        return $this->respondWithSuccessWithData($colors);
    }
}
