<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class PaymentDetails extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\PaymentDetails>
     */
    public static $model = \App\Models\PaymentDetails::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'tran_id';
    public static $group = 'Orders';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','tran_id',
    ];

    public static function label()
    {
        return __('Order Payment Details');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
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
//            tran id
            Text::make('Transaction id', 'tran_id')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            val
            Text::make('Value id', 'val_id')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            amount
            Number::make('amount')
                ->min(0)
                ->step('any')
                ->rules('required'),
//            store amount store_amount
            Number::make('Store amount','store_amount')
                ->min(0)
                ->step('any')
                ->rules('required'),
//            bank tran id
            Text::make('Bank id', 'bank_tran_id')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            status
            Text::make('Status', 'status')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            card type
            Text::make('Card Type', 'card_type')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            card no
            Text::make('Card number', 'card_no')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            trans date
            Text::make('Currency', 'currency')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            currency
            Text::make('Card Issuer', 'card_issuer')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            card issuer
            Text::make('Card Brand', 'card_brand')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            card brand
            Text::make('Card Sub brand', 'card_sub_brand')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            card sub brand
            Text::make('Card Issuer country', 'card_issuer_country')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            card issuer country
            Text::make('Card Country code', 'card_issuer_country_code')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            country code
            Text::make('Currency type', 'currency_type')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            currency type
            Text::make('Currency amount', 'currency_amount')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            amount
            Text::make('Currency Rate', 'currency_rate')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            rate
            Text::make('Card Type', 'card_type')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
//            risk title
            Text::make('Risk Title', 'risk_title')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
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
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function searchableColumns()
    {
        return [
            'id',
            new SearchableRelation('order', 'transaction_id'),
        ];
    }
}
