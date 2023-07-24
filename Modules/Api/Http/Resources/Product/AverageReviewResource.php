<?php
namespace Modules\Api\Http\Resources\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class AverageReviewResource extends JsonResource{
    public function toArray($request){
        return [
            'average' => $this->average,
            'total' => $this->total,
        ];
    }
}
