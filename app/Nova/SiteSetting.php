<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class SiteSetting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\System\SiteSetting>
     */
    public static $model = \App\Models\System\SiteSetting::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

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
//          name
            Text::make('Site Name', 'name')
                ->sortable()
                ->rules('nullable', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter site name',
                    ],
                ]),
//          email
            Text::make('Site Email', 'email')
                ->sortable()
                ->rules('nullable', 'email')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter email',
                    ],
                ]),
//
            Text::make('Site Phone', 'phone')
                ->sortable()
                ->rules('nullable', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter number',
                    ],
                ]),
//          header logo
            Image::make('Header Logo', 'header_logo')
                ->path('site')
                ->disk('public')
                ->nullable()
                ->disableDownload(),
//          footer logo
            Image::make('Footer Logo', 'footer_logo')
                ->path('site')
                ->disk('public')
                ->nullable()
                ->disableDownload(),
//          fav icon
            Image::make('Fav Icon', 'fav_icon')
                ->path('site')
                ->disk('public')
                ->nullable()
                ->disableDownload(),
//          fav icon
            Image::make('Dark fav Icon', 'dark_fav_icon')
                ->path('site')
                ->disk('public')
                ->nullable()
                ->disableDownload(),
//          facebook url
            Text::make('Facebook Url', 'facebook_url')
                ->sortable()
                ->rules('nullable', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter url',
                    ],
                ]),
//          instagram url
//            Text::make('Instagram url', 'instagram_url')
//                ->sortable()
//                ->rules('nullable', 'max:255')
//                ->withMeta([
//                    'extraAttributes' => [
//                        'placeholder' => 'Enter url',
//                    ],
//                ]),
//          twitter url
            Text::make('Twitter url', 'twitter_url')
                ->sortable()
                ->rules('nullable', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter url',
                    ],
                ]),
//          youtube url
            Text::make('Youtube url', 'whatsapp_url')
                ->sortable()
                ->rules('nullable', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter url',
                    ],
                ]),
//          linkedin url
            Text::make('Linkedin url', 'youtube_url')
                ->sortable()
                ->rules('nullable', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter url',
                    ],
                ]),
//            site address
            Text::make('Site address', 'site_address')
                ->sortable()
                ->rules('nullable', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter address',
                    ],
                ]),
            //             date
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

    public static function searchable()
    {
        return false;
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }
}
