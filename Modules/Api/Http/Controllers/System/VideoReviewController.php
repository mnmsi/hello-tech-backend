<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\VideoReviews;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Http\Resources\System\VideoReviewResource;


class VideoReviewController extends Controller
{
    public function index()
    {
        // Check if the video reviews are cached
        if (Cache::has('video_reviews')) {
            return $this->respondWithSuccessWithData(Cache::get('video_reviews'));
        }

        $data = VideoReviews::all();
        $data = VideoReviewResource::collection($data);

        // cache the response forever
        Cache::forever('video_reviews', $data);

        return $this->respondWithSuccessWithData($data);
    }
}
