<?php

namespace App\Nova;

use App\Models\System\City;
use App\Models\System\Division;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class UserAddress extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User\UserAddress>
     */
    public static $model = \App\Models\User\UserAddress::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    public static $group = 'User';

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
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
//            user
            BelongsTo::make('User', 'user')
                ->rules('required')
                ->noPeeking(),
            Select::make('Type', 'type')->options([
                'home' => 'Home',
                'work' => 'Work',
                'other' => 'Other',
            ])->rules('required'),
//            name
            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter name',
                    ],
                ]),
            Text::make('Address Line', 'address_line')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter address line',
                    ],
                ]),
//            phone
            Text::make('Phone', 'phone')
                ->sortable()
                ->rules('required', 'max:20')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter number',
                    ],
                ]),
//            division
            BelongsTo::make('Division', 'division','App\Nova\Others\Division')
                ->rules('required')
                ->searchable()
                ->noPeeking(),
//            city
            BelongsTo::make('City', 'city','App\Nova\Others\City')
                ->rules('required')
                ->searchable()
                ->noPeeking(),
//            area
            BelongsTo::make('Area', 'area','App\Nova\Others\Area')
                ->rules('required')
                ->searchable()
                ->noPeeking(),
//            default
            Select::make('Default Address', 'is_default')->options([
                '1' => 'Yes',
                '0' => 'No',
            ])->rules('required')
                ->resolveUsing(function ($value) {
                    if (!$value) {
                        return 0;
                    }
                    return 1;
                })
                ->displayUsing(function ($v) {
                    return $v ? "Yes" : "No";
                }),
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

    public static function searchableColumns()
    {
        return ['id', new SearchableRelation('user', 'email')];
    }
}
