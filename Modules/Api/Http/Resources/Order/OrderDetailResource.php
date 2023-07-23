<?php
namespace Modules\Api\Http\Resources\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource{
    public function toArray($request)
    {
       return [
           'id' => $this->id,
           'quantity' => $this->quantity,
           'price' => $this->price,
           'name' => $this->product->name,
           'product_image_url' => str_contains($this->product->image_url, 'http') ? $this->product->image_url : asset('storage/'.$this->product->image_url),
       ];
    }
}
