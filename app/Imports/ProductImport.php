<?php

namespace App\Imports;

use App\Models\Product\Product;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
//        dd($row);
//        dd(file_get_contents($row[3]));
//        $contents = file_get_contents($row[3]);
//        $name = substr($row[3], strrpos($row[3], '/') + 1);
//        $fff = Storage::put($name, $contents);
//        $url = $row[3];
        $imageUrl = $row[3];

// Download the image content

        if($imageUrl)
        {
            $response = Http::get($imageUrl);
            if ($response->successful()) {
                $imageData = $response->body();
                // Generate a unique file name or use the original name
                $fileName = 'unique_filename.jpg';
                // Save the image to your desired storage disk
                Storage::disk('public')->put($fileName, $imageData);

                // Optionally, you can store the file path in your database
                // Example:
                $imageUrl = Storage::disk('public')->url($fileName);

                // $imageUrl can be saved in your database or used for display
            } else {
                // Handle the case when the HTTP request to the image URL fails
            }
        }

        return new Product([
            'brand_id' => 3,
            'category_id' => 2,
            'name' => $row[6],
            'price' => (integer)$row[8],
            'video_url' => $row[5],
            'product_code' => $row[7],
            'image_url' => $imageUrl,
            'description' => $row[11],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
