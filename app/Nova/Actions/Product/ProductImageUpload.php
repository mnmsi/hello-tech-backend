<?php

namespace App\Nova\Actions\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProductImageUpload extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Upload Product Images';
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
        dd($fields);
        //
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
                <div class='text-secondary m-0 font-bold'>
                    <span class='text-red-500 text-sm'>*</span>
                    Select Multiple Image with product code.
                </div>
            ")->asHtml(),
            File::make('Images', 'images')
                ->acceptedTypes('image/*')
                ->required(),
        ];
    }
}
