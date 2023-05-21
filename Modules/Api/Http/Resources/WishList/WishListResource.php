<?php

namespace Modules\Api\Http\Resources\WishList;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Api\Http\Traits\Product\ProductTrait;


class WishListResource extends JsonResource
{
    use ProductTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'brand' => $this->product->brand->name,
            'discount_price' => $this->calculateDiscountPrice($this->product->price,$this->product->discount_rate),

        ];
    }
}
