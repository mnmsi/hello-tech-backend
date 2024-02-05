<?php

namespace Modules\Api\Http\Traits\Product;

use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\ProductData;
use App\Models\SectionOrder;
use Illuminate\Support\Facades\DB;

trait ProductTrait
{
    /**
     * @param $price
     * @param $discountRate
     * @return float|int
     */
    public function calculateDiscountPrice($price, $discountRate)
    {
        return round($price - ($price * $discountRate / 100));
    }

    public function getSearchSuggestions($search)
    {
        $searchItem = explode(' ', $search);
        $query = Product::where('is_active', 1);
        foreach ($searchItem as $item) {
            $query->where('name', 'like', '%' . $item . '%');
        }
        return $query->take(5)->get();

//        $results = DB::table('products')->whereRaw("name REGEXP '".implode('|', explode(' ', $search))."'")->get();
//        return Product::where('name', 'RLIKE', '%'.$text.'%')->take(5)->get();
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function getProductDetails($productId)
    {
        return Product::find($productId);
    }

    public function featuredProduct($categoryId)
    {
        return Product::where('category_id', $categoryId)->where('is_featured', 1)->orderByRaw('ISNULL(category_order_no), category_order_no ASC')->get();
    }

    public function initializeFilterData($request)
    {
        return [
            'name' => $request->name ?? null,
            'category' => $request->category ?? null,
            'brand' => $request->brand ?? null,
            'is_official' => $request->is_official ?? null,
            'value' => $request->value ?? null,
            'short_by' => $request->short_by ?? null,
            'price_from' => $request->price_from ?? null,
            'price_to' => $request->price_to ?? null,
        ];
    }

    public function getProductsQuery($params)
    {
        $order = 'category_order_no';
        if ($params['category'] == 'gadgets') {
            $params['category'] = null;
            $order = 'order_no';
        }
        $name = explode(' ', $params['name']);
        $query = Product::wherehas('colors', function ($q) {
            $q->where('id', '>', 0);
        })->where('is_active', 1);
        foreach ($name as $item) {
            $query->where('name', 'like', '%' . $item . '%');
        }
        return $query->when($params['brand'], function ($query) use ($params) {
            $query->where('brand_id', $params['brand']);
        })
            ->when($params['category'], function ($query) use ($params) {
                $query->where(function ($c) use ($params) {
                    $c->whereHas('category', function ($query) use ($params) {
                        $query->where('slug', $params['category']);
                    })->orWhereHas('subCategory', function ($query) use ($params) {
                        $query->where('slug', $params['category']);
                    });
                });
            })
            ->when($params['is_official'], function ($query) use ($params) {
                $query->where('is_official', $params['is_official']);
            })->when($params['value'], function ($query) use ($params) {
                $query->whereHas('metaValue', function ($query) use ($params) {
                    $query->whereIn('id', explode(',', $params['value']));
                });
            })->when($params['price_from'], function ($query) use ($params) {
                $query->where('price', '>=', $params['price_from']);
            })->when($params['price_to'], function ($query) use ($params) {
                $query->where('price', '<=', $params['price_to']);
            })
            ->when($params['short_by'], function ($query) use ($params) {
                $query->orderBy('price', $params['short_by']);
            })->orderByRaw('ISNULL(' . $order . '), ' . $order . ' ASC')
            ->paginate(27);

//        return Product::wherehas('colors', function ($q) {
//            $q->where('id', '>', 0);
//        })->where('is_active', 1)
//            ->when($params['name'], function ($query) use ($params) {
//
//            })
//            ->when($params['brand'], function ($query) use ($params) {
//                $query->where('brand_id', $params['brand']);
//            })
//            ->when($params['category'], function ($query) use ($params) {
//                $query->where(function ($c) use ($params) {
//                    $c->whereHas('category', function ($query) use ($params) {
//                        $query->where('slug', $params['category']);
//                    })->orWhereHas('subCategory', function ($query) use ($params) {
//                        $query->where('slug', $params['category']);
//                    });
//                });
//            })
//            ->when($params['is_official'], function ($query) use ($params) {
//                $query->where('is_official', $params['is_official']);
//            })->when($params['value'], function ($query) use ($params) {
//                $query->whereHas('metaValue', function ($query) use ($params) {
//                    $query->whereIn('id', explode(',', $params['value']));
//                });
//            })->when($params['price_from'], function ($query) use ($params) {
//                $query->where('price', '>=', $params['price_from']);
//            })->when($params['price_to'], function ($query) use ($params) {
//                $query->where('price', '<=', $params['price_to']);
//            })
//            ->when($params['short_by'], function ($query) use ($params) {
//                $query->orderBy('price', $params['short_by']);
//            })->orderByRaw('ISNULL(' . $order . '), ' . $order . ' ASC')
//            ->paginate(27);
    }

    public function getProductDetailsBySlug($slug)
    {
        return Product::where('slug', $slug)->with(['productFeatureKeys' => function ($q) {
            $q->with(['productFeatureValues' => function ($q) {
                $q->where('stock', '>', 0);
            }]);
        }, 'banner', 'category', 'colors' => function ($c) {
            $c->where('id', '>', 0);
        }])->first();
    }

    public function productDataById($id)
    {
        return ProductData::wherehas('colors', function ($q) {
            $q->where('id', '>', 0);
        })->where('product_feature_value_id', $id)->orWhere('product_color_id', $id)->first();
    }

    public function getRelatedProduct()
    {
        return Product::where('is_active', 1)
        ->orderByRaw('ISNULL(order_no), order_no ASC')->take(4)->get();
    }

    public function getProductByBrandSlug($slug)
    {
        return Product::where
        ('is_active', 1)
            ->whereHas('brand', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->orderByRaw('ISNULL(order_no), order_no ASC')->get();;
    }

    public function getNewArrivals()
    {
        $data = SectionOrder::where('section', 'new-arrivals')
            ->with(['sectionOrderProducts' => function ($q) {
                $q->with(
                    ['product' => function ($q) {
                        $q->wherehas('colors', function ($q) {
                            $q->where('id', '>', 0);

                        });
                    }]
                )->orderBy('order', 'asc');
            }])->get();
        return $data->pluck('sectionOrderProducts')->flatten()->pluck('product');
    }

    public function getFeaturedNewArrivals()
    {
        //        section order with products
        $data = SectionOrder::where('section', 'new-arrivals')
            ->with(['sectionOrderProducts' => function ($q) {
                $q->with(
                    ['product' => function ($q) {
                        $q->wherehas('colors', function ($q) {
                            $q->where('id', '>', 0);
                        });
                    }]
                )->limit(8)->orderBy('order', 'asc');
            }])->get();
        return $data->pluck('sectionOrderProducts')->flatten()->pluck('product');
    }
}
