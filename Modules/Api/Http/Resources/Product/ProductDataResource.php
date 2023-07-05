<?php

namespace Modules\Api\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ProductDataResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_color_id' => $this->product_color_id,
            'product_feature_key_id' => $this->product_feature_key_id,
            'product_id' => $this->product_id,
            'price' => $this->price,
            'stock' => $this->stock,
        ];
    }
}
