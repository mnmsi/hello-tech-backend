<?php

namespace App\Imports;

use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductColorImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $product = Product::where("product_code", $row[0])->first();
        if ($product) {
//            $p_specification = [];
//            if(!empty($row[10]) && !empty($row[11]) && !empty($row[12])) {
//                $p_specification[] = [
//                    "product_id" => $product->id,
//                    "title" => $product->id,
//                    "value" => $product->id,
//                    "product_id" => $product->id,
//                ];
//            }
//            product specification
//            dd($row);
//            product color create
            return new ProductColor([
                "product_id" => $product->id,
                "name" => $row[1],
                "color_code" => $row[2],
                "price" => $row[3],
                "stock" => $row[4],
            ]);
        } else {
            return null;
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
