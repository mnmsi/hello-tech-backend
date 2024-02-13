<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\Testimonial;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Http\Resources\System\TestimonialResource;

class TestimonialController extends Controller
{
    public function testimonials()
    {
        //  Check if the testimonials are cached
        if (Cache::has('testimonials')) {
            return $this->respondWithSuccessWithData(Cache::get('testimonials'));
        }

        // Get all active testimonials
        $testimonials = Testimonial::where('is_active', 1)
                                   ->get();

        $testimonials = TestimonialResource::collection($testimonials);

        Cache::forever('testimonials', $testimonials);

        // Return response with testimonials
        return $this->respondWithSuccessWithData($testimonials);
    }
}
