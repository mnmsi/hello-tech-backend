<?php

namespace Modules\Api\Http\Resources\Cart;

use App\Models\ProductFeatureValue;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Api\Http\Traits\Order\CartTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;

class CartResource extends JsonResource
{
    use ProductTrait, CartTrait;

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
            'name' => $this->product->name ?? '',
            'product_code' => $this->product->product_code ?? '',
            'product_id' => $this->product_id,
            'product_color_id' => $this->product_color_id,
            'price' => $this->calculateDiscountPrice($this->product->price ?? 0, $this->product->discount_rate ?? 0),
            'image_url' => $this->product->image ?? str_contains($this->product->image_url, 'http') ? $this->product->image_url : asset('storage/' . $this->product->image_url),
            'color_name' => $this->productColor->name ?? '',
            'color_price' => $this->productColor->price ?? '',
            'total_price' => $this->calculateCardAmount($this),
        ];
    }
}
