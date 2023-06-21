<?php

namespace Modules\Api\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Modules\Api\Http\Requests\Product\PreOrderRequest;
use Modules\Api\Http\Traits\Product\PreOrderTrait;


class PreOrderController extends Controller
{
    use PreOrderTrait;

    public function store(PreOrderRequest $request)
    {
        $data = $this->storePreOrder($request);
        if ($data === true) {
            return $this->respondWithSuccess([
                'message' => 'Pre Order Successfully',
            ]);
        } else {
            return $this->respondError($data);
        }
    }
}
