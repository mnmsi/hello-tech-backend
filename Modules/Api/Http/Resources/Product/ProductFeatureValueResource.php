<?php

namespace Modules\Api\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ProductFeatureValueResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'stock' => $this->stock,
            'price' => $this->price,
        ];
    }
}
