<?php

namespace App\Imports;

use App\Models\Product\Brand;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \ErrorException
     */
    public function model(array $row)
    {
        $brand = Brand::where("name", $row[0])->first();
        $category = Category::where("name", $row[1])->first();
        if ($brand == null && !empty($row[0])) {
            throw new \ErrorException('Brand ' . $row[0] . " missing. Please add brand first.");
        }
        if ($category == null && !empty($row[1])) {
            throw new \ErrorException('Category ' . $row[1] . " missing. Please add category first.");
        }
        if (!Product::where("product_code", $row[7])->first() && !empty($row[0])) {
            return new Product([
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'sub_category_id' => $brand->sub_category_id ?? null,
                'name' => $row[6],
                'price' => (integer)$row[8],
                'discount_rate' => (integer)$row[9],
                'is_featured' => Str::upper($row[14]) == "YES" ? 1 : 0,
                'is_new_arrival' => Str::upper($row[12]) == "YES" ? 1 : 0,
                'is_active' => 1, // $row[15]
                'is_official' => Str::upper($row[13]) == "YES" ? 1 : 0,
                'product_code' => $row[7],
                'image_url' => "product_image/gtu1P52sVj8gzp6LBvH7Oto0fj6sPkOyREOOZD6X.png",
                'video_url' => $row[5],
                'description' => $row[11],
            ]);
        }
        return null;
    }

    public function startRow(): int
    {
        return 2;
    }
}
