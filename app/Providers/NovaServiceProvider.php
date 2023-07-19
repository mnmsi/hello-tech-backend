<?php

namespace App\Providers;

use App\Nova\AboutUs;
use App\Nova\Banner;
use App\Nova\BikeSellRequest;
use App\Nova\BodyType;
use App\Nova\Brand;
use App\Nova\Category;
use App\Nova\Dashboards\Main;
use App\Nova\DeliveryOption;
use App\Nova\HomeSection;
use App\Nova\Notification;
use App\Nova\Order;
use App\Nova\OrderDetail;
use App\Nova\PaymentDetails;
use App\Nova\PaymentMethods;
use App\Nova\PrivacyPolicy;
use App\Nova\Product;
use App\Nova\ProductColor;
use App\Nova\ProductMedia;
use App\Nova\ProductReview;
use App\Nova\ProductSpecification;
use App\Nova\SellBike;
use App\Nova\SeoSetting;
use App\Nova\ShippingCharge;
use App\Nova\Showroom;
use App\Nova\SiteSetting;
use App\Nova\TermsAndCondition;
use App\Nova\Testimonial;
use App\Nova\User;
use App\Nova\UserAddress;
use App\Nova\UserWishlist;
use App\Nova\VideoReviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::footer(function ($req) {
            return Blade::render('nova/footer');
        });

//        menu
        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),
//                user users
                MenuSection::make('Users', [
                    MenuItem::resource(User::class),
                    MenuItem::resource(UserAddress::class),
                    MenuItem::resource(UserWishlist::class),
                ])->icon('users')->collapsable(),
//                product
                MenuSection::make('Products', [
                    MenuItem::resource(Product::class),
                    MenuItem::resource(ProductColor::class),
                    MenuItem::resource(ProductSpecification::class),
                    MenuItem::resource(ProductMedia::class),
                    MenuItem::resource(ProductReview::class),
                ])->icon('gift')->collapsable(),
//                order
                MenuSection::make('Orders', [
                    MenuItem::resource(Order::class),
                    MenuItem::resource(OrderDetail::class),
                    MenuItem::resource(PaymentDetails::class),
                ])->icon('shopping-cart')->collapsable(),
//                bike sell
                MenuSection::make('Bike Sell', [
                    MenuItem::resource(SellBike::class),
                    MenuItem::resource(BikeSellRequest::class),
                ])->icon('cube')->collapsable(),
//                system
                MenuSection::make('System', [
//                    MenuItem::resource(Showroom::class),
                    MenuItem::resource(Brand::class),
                    MenuItem::resource(Category::class),
                    MenuItem::resource(Banner::class),
                    MenuItem::resource(BodyType::class),
                    MenuItem::resource(PaymentMethods::class),
                    MenuItem::resource(ShippingCharge::class),
                    MenuItem::resource(DeliveryOption::class),
                    MenuItem::resource(VideoReviews::class),
                    MenuItem::resource(Notification::class),
                ])->icon('briefcase')->collapsable(),
//                settings
                MenuSection::make('Settings', [
                    MenuItem::resource(SiteSetting::class),
                    MenuItem::resource(SeoSetting::class),
                    MenuItem::resource(HomeSection::class),
//                    MenuItem::resource(Testimonial::class),
                    MenuItem::resource(TermsAndCondition::class),
                    MenuItem::resource(PrivacyPolicy::class),
                    MenuItem::resource(AboutUs::class)->name('About Us'),
                ])->icon('document-text')->collapsable(),
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
//            return in_array($user->role_id, [
//                1
//            ]);
            return $user->role_id == 1;
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
