<?php

namespace App\Nova\Actions\Product;

use App\Imports\ProductImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
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
     * @return mixed
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
    public function fields(NovaRequest $request)
    {
        return [
            File::make("Products","products")->required(),
        ];
    }
}
