<?php
namespace Modules\Api\Http\Resources\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource{
    public function toArray($request)
    {
       return [
           'id' => $this->id,
       ];
    }
}
