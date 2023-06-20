<?php

namespace Modules\Api\Http\Resources\System;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'page'      => $this->page,
            'show_on'   => $this->show_on,
            'image_url' => str_contains($this->image_url, 'https') ? $this->image_url : asset('storage/' . $this->image_url),
        ];
    }
}
