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
            'total_price' => $this->total_price,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d M Y'),
            'orders'=> OrderDetailResource::collection($this->orderDetails),
        ];
    }
}
