<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\VideoReviews;
use Modules\Api\Http\Resources\System\VideoReviewResource;


class VideoReviewController extends Controller
{
    public function index()
    {
        $data = VideoReviews::all();
        return $this->respondWithSuccessWithData(
            VideoReviewResource::collection($data),
        );
    }
}
