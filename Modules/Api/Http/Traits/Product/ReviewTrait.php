<?php

namespace Modules\Api\Http\Traits\Product;

use App\Models\Product\ProductReview;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Http\Requests\Product\ReviewRequest;

trait ReviewTrait
{
    public function getReview($id)
    {
        return ProductReview::where('product_id', $id)->get();
    }

    public function storeReview($data)
    {
        $data['user_id'] = Auth::id();
        return ProductReview::create($data);
    }

    public function getTotalReview($id)
    {
        return ProductReview::where('product_id', $id)->get();
    }
}
