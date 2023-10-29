<?php

namespace App\Nova\Actions\Product;

use App\Imports\ProductColorImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Facades\Excel;

class ProductColor extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Import Product Colors';
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
            Excel::import(new ProductColorImport(), $fields->product_colors);
            return Action::message("Import Product Colors done!");
        } catch (\Exception $e) {
            return Action::danger($e->getMessage());
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Heading::make("
                <div class='text-secondary m-0 text-danger'>
                    <span class='text-red-500 text-sm'>*</span>
                    <span class='font-bold text-sm'>FORMAT:</span>
                    Product Code <span class='text-red-500 text-sm'>|</span>
                    Color Name <span class='text-red-500 text-sm'>|</span>
                    Color Code <span class='text-red-500 text-sm'>|</span>
                    Color Price <span class='text-red-500 text-sm'>|</span>
                    Color Stock <span class='text-red-500 text-sm'>|</span>
                    Product Image-1 <span class='text-red-500 text-sm'>|</span>
                    Product Image-2 <span class='text-red-500 text-sm'>|</span>
                    Product Image-3 <span class='text-red-500 text-sm'>|</span>
                    Product Image-4 <span class='text-red-500 text-sm'>|</span>
                    Product Image-5 <span class='text-red-500 text-sm'>|</span>
                    Product Warranty-1 Name <span class='text-red-500 text-sm'>|</span>
                    Product Warranty-1 Price <span class='text-red-500 text-sm'>|</span>
                    Product Warranty-1 Stock <span class='text-red-500 text-sm'>|</span>
                    Product Warranty-2 Name <span class='text-red-500 text-sm'>|</span>
                    Product Warranty-2 Price <span class='text-red-500 text-sm'>|</span>
                    Product Warranty-2 Stock <span class='text-red-500 text-sm'>|</span>
                    Product Size-1 Meta Key 1  <span class='text-red-500 text-sm'>|</span>
                    Product Size-2 Meta Key 2 <span class='text-red-500 text-sm'>|</span>
                    <span class='text-red-500 text-sm'>*Use CSV file</span></div>
            ")->asHtml(),
            File::make("Products Colors", "product_colors")->required(),
        ];
    }
}
