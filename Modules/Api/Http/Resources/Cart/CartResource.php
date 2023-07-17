<?php

namespace Modules\Api\Http\Resources\Cart;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Api\Http\Traits\Product\ProductTrait;

class CartResource extends JsonResource
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
            'quantity' => $this->quantity,
            'is_checked' => $this->status,
            'product_id' => $this->product_id,
            'product_color_id' => $this->product_color_id,
            'product_data_id' => $this->product_data_id,
            'name' => $this->product->name ?? '',
            'price' => $this->checkPrice(),
            'price_after_discount' => $this->checkColorForPrice(),
            'image_url' => $this->product->image ?? str_contains($this->product->image_url, 'http') ? $this->product->image_url : asset('storage/' . $this->product->image_url),
            'color_name' => $this->productColor->name ?? '',
        ];
    }

    public function checkColorForPrice()
    {
        return !$this->product_data_id
            ? $this->calculateDiscountPrice($this->product->price,$this->product->discount_rate)
            : $this->productData->price;
    }

    public function checkPrice()
    {
        return !$this->product_data_id
            ? $this->product->price
            : $this->productData->price;
    }
}
