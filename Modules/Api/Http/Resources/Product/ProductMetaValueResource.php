<?php
namespace Modules\Api\Http\Resources\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMetaValueResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'value' => $this->value,
        ];
    }
}
