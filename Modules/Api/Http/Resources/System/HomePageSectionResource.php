<?php

namespace Modules\Api\Http\Resources\System;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Http\Resources\Product\AccessoryCollection;
use Modules\Api\Http\Resources\Product\BikeCollection;

class HomePageSectionResource extends JsonResource
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
            'section_title' => $this->section_title,
            'products' => HomePageSectionProductResource::collection($this->homePageSection),
        ];
    }
}
