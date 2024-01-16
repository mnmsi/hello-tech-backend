<?php

namespace Modules\Api\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Http\Resources\System\BannerResource;
use Modules\Api\Http\Traits\Product\FeatureTrait;
use Modules\Api\Http\Traits\Product\ProductTrait;

class ProductDetailsResource extends JsonResource
{
    use ProductTrait;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'is_favorite' => $this->is_favorite,
            'offer_price' => $this->discount_rate != 0 ? $this->calculateDiscountPrice($this->price, $this->discount_rate) : null,
            'product_code' => $this->product_code,
            'image_url' => str_contains($this->image_url, 'http') ? $this->image_url : asset('storage/' . $this->image_url),
            'brand' => new BrandResource($this->brand),
            'colors' => $this->colors ? ColorResource::collection($this->colors) : [],
            'features' => $this->productFeatureKeys ? ProductFeatureResource::collection($this->productFeatureKeys) : [],
            'medias' => $this->media ? MediaResource::collection($this->media) : [],
            'specifications' => $this->specifications ? SpecificationResource::collection($this->specifications) : [],
            'description' => $this->description,
            'video_url' => $this->video_url ? $this->video_url : '',
            'banner' => $this->banner ? new BannerResource($this->banner) : [],
            'category' => new CategoryResource($this->category),
            'sub_category' => new CategoryResource($this->subCategory),
            'is_stock_out' => $this->colors->sum('stock') == 0,

        ];
    }
}
