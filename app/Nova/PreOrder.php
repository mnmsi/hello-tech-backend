<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use YieldStudio\NovaPhoneField\PhoneNumber;

class PreOrder extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\PreOrder>
     */
    public static $model = \App\Models\PreOrder::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'product_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'product_name', 'email', 'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
//            name
            Text::make('Product Name', 'product_name')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter name',
                    ],
                ]),
//            image
            Image::make('Product Image', 'product_image')
                ->path('pre_order')
                ->disk('public')
                ->creationRules('required')
                ->updateRules('nullable')
                ->disableDownload(),
//            quantity
            Number::make('Quantity', 'product_quantity')
                ->min(1)
                ->step('any')
                ->rules('required'),
//            name
            Text::make('Customer Name', 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter name',
                    ],
                ]),
//            email
            Text::make('Email','email')
                ->sortable()
                ->rules('required', 'email', 'max:254'),
//            number
            PhoneNumber::make('Phone', 'phone')
                ->withCustomFormats('880## #### ####')
                ->onlyCustomFormats()
                ->help("Ex: 880 #### #####"),
//            address
            Textarea::make('Address', 'address')
                ->sortable()
                ->rules('required')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter address',
                    ],
                ]),
//            date
            DateTime::make('Created At', 'created_at')
                ->hideFromIndex()
                ->default(now())
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            DateTime::make('Updated At', 'updated_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->default(now()),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
