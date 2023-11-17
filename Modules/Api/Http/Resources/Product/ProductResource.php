<?php

namespace Modules\Api\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Http\Traits\Product\FeatureTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;

class ProductResource extends JsonResource
{
    use ProductTrait;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'price_after_discount' => $this->discount_rate !=0 ? $this->calculateDiscountPrice($this->price, $this->discount_rate) : null,
            'image_url' => str_contains($this->image_url, 'http') ? $this->image_url : asset('storage/' . $this->image_url),
            'hover_image_url' => $this->hover_image_url ? asset('storage/' . $this->hover_image_url) : null,
            'is_favorite' => $this->is_favorite,
            'is_cart' => $this->is_cart,
        ];
    }
}
