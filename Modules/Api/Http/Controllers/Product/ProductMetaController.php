<?php
namespace Modules\Api\Http\Controllers\Product;
use \App\Http\Controllers\Controller;
use Modules\Api\Http\Resources\Product\ProductMetaResource;
use Modules\Api\Http\Traits\Product\ProductMetaTrait;
class ProductMetaController extends Controller
{
    use ProductMetaTrait;
    public function productMeta($id)
    {
        return $this->respondWithSuccessWithData(ProductMetaResource::collection($this->getMetaByCategory($id)));
    }
}
