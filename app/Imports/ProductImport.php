<?php

namespace App\Imports;

use App\Models\Product\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'brand_id' => 3,
            'category_id' => 2,
            'name' => $row[6],
            'price' => (integer)$row[8],
            'video_url' => (integer)$row[5],
            'product_code' => (integer)$row[7],
            'image_url' => "https://images.entitysport.com/assets/uploads/2023/07/Kerala.png",
            'description' => $row[11],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
