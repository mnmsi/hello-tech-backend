<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Product\Category;
use Laravel\Nova\Query\Search\SearchableRelation;

class Brand extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Product\Brand>
     */
    public static $model = \App\Models\Product\Brand::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';
    public static $group = 'System';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
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

            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter name',
                    ],
                ]),

            Image::make('Image', 'image_url')
                ->disk('public')
                ->help("use image size 70x70 background transparent and extension .png")
                ->path('brand')
                ->creationRules('required')
                ->updateRules('nullable')
                ->disableDownload(),

            Text::make('Slug', 'slug')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            BelongsTo::make('Category', 'category')
                ->rules('required')
                ->noPeeking(),

            Select::make('Is popular', 'is_popular')->options([
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
                    return $v ? "Popular" : "Unpopular";
                }),

            Select::make('Status', 'is_active')->options([
                '1' => 'Yes',
                '0' => 'No',
            ])->rules('required')
                ->resolveUsing(function ($value) {
                    if ($value === false) {
                        return 0;
                    }
                    return 1;
                })
                ->displayUsing(function ($v) {
                    return $v ? "Active" : "Inactive";
                }),

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
        return ['id', new SearchableRelation('category', 'name')];
    }


}
