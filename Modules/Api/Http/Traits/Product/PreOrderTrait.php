<?php

namespace Modules\Api\Http\Traits\Product;

use App\Models\PreOrder;

trait PreOrderTrait
{

    public function storePreOrder($request)
    {
        try {
            $data = [
                'product_name' => $request->product_name,
                'name' => $request->name,
                'phone' => $request->phone,
                'product_quantity' => $request->product_quantity,
                'email' => $request->email ?? null,
                'address' => $request->address ?? null,
            ];
            if ($request->hasFile('product_image')) {
                $data['product_image'] = $request->product_image->store('pre_order', 'public');
            }
            PreOrder::create($data);
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
