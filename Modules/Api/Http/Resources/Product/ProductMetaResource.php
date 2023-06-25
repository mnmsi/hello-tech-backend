<?php

namespace Modules\Api\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMetaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'key' => $this->key,
            'values' => ProductMetaValueResource::collection($this->whenLoaded('productMetaValues')),
        ];
    }
}
