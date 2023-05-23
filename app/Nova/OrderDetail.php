<?php

namespace App\Nova;

use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class OrderDetail extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<OrderDetails>
     */
    public static $model = OrderDetails::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    public static $group = 'Orders';
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
//            order
            BelongsTo::make('Order', 'order')
                ->rules('required')
                ->noPeeking(),
//            product
            BelongsTo::make('Product', 'product')
                ->rules('required')
                ->noPeeking(),
//            product color
            BelongsTo::make('Product Color', 'product_color')
                ->rules('required')
                ->noPeeking(),
//            price
            Number::make('price')
                ->min(0)
                ->step('any')
                ->rules('required'),
//            quantity
            Number::make('quantity')
                ->min(0)
                ->step('any')
                ->rules('required'),
//            total
            Number::make('total')
                ->min(0)
                ->step('any')
                ->rules('required'),
//            discount
            Number::make('discount_rate')
                ->min(0)
                ->step('any')
                ->nullable(),
//            subtotal
            Number::make('subtotal_price')
                ->min(0)
                ->step('any')
                ->nullable(),
//            date
            DateTime::make('Created At', 'created_at')
                ->hideFromIndex()
                ->default(now())
                ->hideWhenUpdating(),

            DateTime::make('Updated At', 'updated_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->default(now()),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
