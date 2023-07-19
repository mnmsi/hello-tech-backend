<?php
namespace Modules\Api\Http\Resources\Cart;
use App\Models\ProductFeatureValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class FeatureResource extends JsonResource{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->value,
            'price' => $this->price,
        ];
    }
}
