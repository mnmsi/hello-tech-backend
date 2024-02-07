<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use YieldStudio\NovaPhoneField\PhoneNumber;

class Notification extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\System\Notification>
     */
    public static $model = \App\Models\System\Notification::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

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
//            phone
            PhoneNumber::make('Phone', 'phone')
                ->withCustomFormats('880##########')
                ->onlyCustomFormats()
                ->creationRules('unique:App\Models\System\Notification,phone')
                ->updateRules('unique:App\Models\System\Notification,phone,{{resourceId}}')
                ->help("Ex: 880#########"),
//            email
//            Text::make('Email', 'email')
//                ->sortable()
//                ->rules('nullable', 'email')
//                ->withMeta([
//                    'extraAttributes' => [
//                        'placeholder' => 'Enter email(optional)',
//                    ],
//                ]),
//            status
            Select::make('Status', 'status')->options([
                '1' => 'Yes',
                '0' => 'No',
            ])->rules('required')
                ->displayUsing(function ($v) {
                    return $v ? "Active" : "Inactive";
                }),
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
