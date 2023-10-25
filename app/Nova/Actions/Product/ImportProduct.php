<?php

namespace App\Nova\Actions\Product;

use App\Imports\ProductImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\File;
use Maatwebsite\Excel\Facades\Excel;
class ImportProduct extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Product Upload';
    public $onlyOnIndex = true;
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return \Action|\Laravel\Nova\Actions\ActionResponse
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        try {
            Excel::import(new ProductImport(),$fields->products);
            return Action::message("Product Upload done!");
        } catch (\Exception $e)
        {
            return Action::danger($e->getMessage());
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Heading::make("
                <div class='text-secondary m-0 text-danger'>
                    <span class='text-red-500 text-sm'>*</span>
                    <span class='font-bold text-sm'>FORMAT:</span>
                    Brand <span class='text-red-500 text-sm'>|</span>
                    Category <span class='text-red-500 text-sm'>|</span>
                    Image <span class='text-red-500 text-sm'>|</span>
                    Hover Image <span class='text-red-500 text-sm'>|</span>
                    Image(2) <span class='text-red-500 text-sm'>|</span>
                    Video Url <span class='text-red-500 text-sm'>|</span>
                    Name <span class='text-red-500 text-sm'>|</span>
                    Product Code <span class='text-red-500 text-sm'>|</span>
                    Price <span class='text-red-500 text-sm'>|</span>
                    Discount Percent <span class='text-red-500 text-sm'>|</span>
                    Product serial number <span class='text-red-500 text-sm'>|</span>
                    Description <span class='text-red-500 text-sm'>|</span>
                    Is new arrival <span class='text-red-500 text-sm'>|</span>
                    Is official <span class='text-red-500 text-sm'>|</span>
                    Is featured <span class='text-red-500 text-sm'>|</span>
                    status <span class='text-red-500 text-sm'>|</span>
                    SKU <span class='text-red-500 text-sm'>*Use CSV file</span></div>
            ")->asHtml(),
            File::make("Products","products")->required(),
        ];
    }
}
