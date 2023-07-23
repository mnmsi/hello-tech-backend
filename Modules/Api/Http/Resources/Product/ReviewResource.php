<?php

namespace Modules\Api\Http\Resources\Product;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user_id' => $this->user->id,
            'review' => $this->review,
            'rate' => $this->rate,
            'created_at' => Carbon::parse($this->created_at)->format('d M Y'),
        ];
    }
}
