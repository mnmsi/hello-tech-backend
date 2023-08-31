<?php

namespace Modules\Api\Http\Controllers\Dynamic;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Modules\Api\Http\Resources\Product\ProductDataResource;
use Modules\Api\Http\Resources\Product\ProductResource;
use Modules\Api\Http\Traits\Dynamic\DynamicPageTrait;

class DynamicPageController extends Controller
{
    use DynamicPageTrait;

    public function allBrandProduct(Request $request, $slug)
    {
        try {
            $check = $this->checkPageSlug($slug);

            if ($check) {
                $result = [];
                $brand_list = $this->getPageBrandProduct($check->id);

                if ($brand_list->all_brand === 0) {
                    foreach ($brand_list->pageBrand as $l) {
                        $products = Product::where('brand_id', $l['brand_id'])
                            ->where('is_active', 1)
                            ->when($l['product_count'] != null, function ($q) use ($l) {
                                $q->take($l['product_count']);
                            })
                            ->orderBy('order_no', 'ASC')
                            ->get();
                        foreach ($products as $p) {
                            $result[] = new ProductResource($p);
                        }
                    }
                } else {
                    $result = ProductResource::collection(Product::where('is_active', 1)
                        ->orderBy('order_no', 'ASC')
                        ->get());
                }

                return [
                    'status' => true,
                    'page' => $this->pageDetails($check),
                    'data' => $result,
                ];
            } else {
                return [
                    'status' => false,
                    'message' => "page not found! please check again.",
                ];
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function pageDetails($data)
    {
        return [
            'title' => $data->title,
            'slug' => $data->slug,
        ];
    }

    public function allPromotionalProduct(Request $request)
    {
        try {
            return [
                'status' => true,
                'data' => $this->getAllPromotionalProduct()
            ];
        } catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
