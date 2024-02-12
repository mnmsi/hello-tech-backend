<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\Showroom;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Http\Resources\System\ShowroomResource;

class ShowroomController extends Controller
{
    public function showrooms()
    {
        // Check if showrooms are cached
        if (Cache::has('showrooms')) {
            // Return cached response
            return $this->respondWithSuccessWithData(ShowroomResource::collection(Cache::get('showrooms')));
        }

        // Get all active showrooms
        $showrooms = Showroom::where('is_active', 1)
            ->get();

        // Cache the response forever
        Cache::forever('showrooms', $showrooms);

        // Return response with showrooms
        return $this->respondWithSuccessWithData(ShowroomResource::collection($showrooms));
    }
}
