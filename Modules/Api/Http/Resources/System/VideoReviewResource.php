<?php

namespace Modules\Api\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoReviewResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->video_url,
            'thumbnail' => str_contains($this->video_thumbnail, 'https') ? $this->video_thumbnail : asset('storage/' . $this->video_thumbnail),
        ];
    }
}
