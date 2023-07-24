<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class SeoSetting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\System\SeoSetting>
     */
    public static $model = \App\Models\System\SeoSetting::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'page_title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'page_title'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            //          page title
            Select::make('Page Title', 'page_title')->options([
                'default' => 'Default',
                'about' => 'About',
                'brands' => 'Brands',
                'cart' => 'Cart',
                'checkout' => 'Checkout',
                'forget-password' => 'Forget Password',
                'otp' => 'OTP',
                'reset-password' => 'Reset Password',
                'login' => 'Login',
                'registration' => 'Registration',
                'new-arrivals' => 'New Arrivals',
                'order' => 'Order',
                'order-success' => 'Order Success',
                'pre-order' => 'Pre Order',
                'privacy-policy' => 'Privacy Policy',
                'product' => 'Product',
                'gadgets' => 'Gadgets',
                'home-page' => 'Home Page',
                'profile' => 'Profile',
                'address' => 'Address',
                'change-password' => 'Change Password',
                'terms-and-conditions' => 'Terms-and-conditions',
                'wishlist' => 'Wishlist',
            ])->rules('required'),
//            Text::make('Page Title', 'page_title')
//                ->sortable()
//                ->rules('required', 'max:255')
//                ->withMeta([
//                    'extraAttributes' => [
//                        'placeholder' => 'Enter page title',
//                    ],
//                ]),
            //          page description
            Text::make('Page Description', 'page_description')
                ->sortable()
                ->rules('required')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter description',
                    ],
                ]),
            //            page keywords
            Text::make('Page Keywords', 'page_keywords')
                ->sortable()
                ->rules('required')
                ->help("*use coma between words for SEO purpose.")
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter seo keywords',
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
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function searchable(): bool
    {
        return false;
    }
}
