<?php
namespace Modules\Api\Http\Resources\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_key' => $this->order_key,
//            'price'=> $this->order_->product_id ?? null,
//            'quantity' => $this->quantity,
//            'total' => $this->total,
//            'product_color_id' => $this->product_color_id,
//            'discount_rate' => $this->discount_rate,
//            'price_after_discount' => $this->price_after_discount,
//            'total_stock' => $this->product_color->stock,
//            'image' => asset('storage/'.$this->product->image_url),
//            'color' => $this->product_color->name,
//            'color_image' => asset('storage/'.$this->product_color->image_url),
        ];
    }
}
