<?php

namespace App\Nova;

use App\Nova\Filters\BannerStatusFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Banner extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\System\Banner>
     */
    public static $model = \App\Models\System\Banner::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'page';
    public static $group = 'System';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'page',
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
            Select::make('Type', 'type')->options([
                'product' => 'For Products',
                'category' => 'For Categories',
                'page' => 'For Pages',
            ])->rules('required'),
//            product
            BelongsTo::make('Product', 'product')
                ->dependsOn(['type'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type == "product") {
                        $field
                            ->rules('required');
                    } else {
                        $field
                            ->hide()
                            ->nullable();
                    }
                })
                ->noPeeking(),
//            category
            BelongsTo::make('Category', 'category')
                ->dependsOn(['type'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type == "category") {
                        $field
                            ->rules('required');
                    } else {
                        $field
                            ->hide()
                            ->nullable();
                    }
                })
                ->noPeeking(),
//          page
            Select::make('Display Page', 'page')->options([
                'home' => 'Home',
                'new-arrivals' => 'New Arrivals',
                'product-detail' => 'Product Details',
            ])->dependsOn(['type'], function (Select $field, NovaRequest $request, FormData $formData) {
                if ($formData->type == "page") {
                    $field
                        ->rules('required');
                } else {
                    $field
                        ->hide()
                        ->nullable();
                }
            }),
//            show on
            Select::make('Page Place', 'show_on')->options([
                'all' => 'All',
                'top' => 'Top',
                'bottom' => 'Bottom',
                'detail' => 'Details',
            ])->dependsOn(['type'], function (Select $field, NovaRequest $request, FormData $formData) {
                if ($formData->type == "page") {
                    $field
                        ->rules('required');
                } else {
                    $field
                        ->hide()
                        ->nullable();
                }
            }),
//            image
            Image::make('Image', 'image_url')
                ->path('banner')
                ->disk('public')
                ->deletable(false)
                ->creationRules('required')
                ->updateRules('nullable')
                ->disableDownload(),
//            status
            Select::make('Status', 'is_active')->options([
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
                    return $v ? "Active" : "Inactive";
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
        return [
            new BannerStatusFilter,
        ];
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
