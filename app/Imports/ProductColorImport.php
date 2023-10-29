<?php

namespace App\Imports;

use App\Models\Product\Product;
use App\Models\Product\ProductColor;
use App\Models\ProductFeatureKey;
use App\Models\ProductFeatureValue;
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
            $count_color = ProductColor::where('product_id', $product->id)->get();
            if (count($count_color) > 0) {
                return null;
            }
            $p_feature_key = ProductFeatureKey::updateOrCreate([
                'product_id' => $product->id,
                'key' => 'Warranty'
            ],[
                'product_id' => $product->id,
                'key' => 'Warranty'
            ]);
//            $p_specification = [];
//            warranty block
            if(!empty($row[10]) && !empty($row[11]) && !empty($row[12])) {
                ProductFeatureValue::create([
                    "product_feature_key_id" => $p_feature_key->id,
                    "title" => $row[10],
                    "value" => $row[11],
                    "stock" => $row[12],
                ]);
            }
            if(!empty($row[13]) && !empty($row[14]) && !empty($row[15])) {
                ProductFeatureValue::create([
                    "product_feature_key_id" => $p_feature_key->id,
                    "title" => $row[13],
                    "value" => $row[14],
                    "stock" => $row[15],
                ]);
            }
//            product specification
//                sub category missing
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
