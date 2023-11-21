<?php

namespace Modules\Api\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ProductFeatureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->productFeatureValues ? $this->key : '',
            'values' => $this->productFeatureValues ? ProductFeatureValueResource::collection($this->productFeatureValues) : [],
        ];
    }
}
