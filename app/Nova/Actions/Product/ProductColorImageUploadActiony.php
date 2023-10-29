<?php

namespace App\Nova\Actions\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductMedia;
use Ayvazyan10\Imagic\Imagic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProductColorImageUploadActiony extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Upload Product Color Images';
    public $onlyOnIndex = true;


    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        try {
            $images_list = json_decode($fields->images, true);

            foreach ($images_list as $m) {
                $fileName = basename($m);
                $image_name = explode('_', pathinfo($fileName, PATHINFO_FILENAME));
                if (count($image_name) == 2) {
                    $product = Product::where("product_code", $image_name[0])->first();
                    if ($product) {
                        $product_color = \App\Models\Product\ProductColor::where([
                            'product_id' => $product->id,
                            'name' => $image_name[1]
                        ])->first();
                        if($product_color) {
                            ProductMedia::create([
                                'product_id' => $product->id,
                                'product_color_id' => $product_color->id,
                                'image_url' => str_replace('/storage', '', $m),
                            ]);
                        } else {
                            return Action::danger('Product color not found with name ' . $image_name[1]);
                        }
                    } else {
                        return Action::danger('Product not found with code ' . $image_name[0]);
                    }
                } else {
                    return Action::danger('Select proper Image color name with productCode_productColorName');
                }
            }
            return Action::message("Product image Upload done!");
        } catch (\Exception $e) {
            return Action::danger($e->getMessage());
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     * @throws \Exception
     */
    public function fields(NovaRequest $request)
    {
        return [
            Heading::make("
                <div class='text-secondary m-0 font-bold'>
                    <span class='text-red-500 text-sm'>*</span>
                    Select Multiple Image with *product code and *color name.
                </div>
            ")->asHtml(),
            Imagic::make('Images', "images")
                ->multiple()
//                ->directory("product_media")
                ->help("Use .png, .jpg images only.")
                ->convert(false)
                ->required(),
        ];
    }
}
