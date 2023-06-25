<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\Auth\ApiAuthController;
use Modules\Api\Http\Controllers\Order\CartController;
use Modules\Api\Http\Controllers\Order\OrderController;
use Modules\Api\Http\Controllers\Order\ShippingChargeController;
use Modules\Api\Http\Controllers\OTP\OtpController;
use Modules\Api\Http\Controllers\Product\BrandController;
use Modules\Api\Http\Controllers\Product\CategoryController;
use Modules\Api\Http\Controllers\Product\PreOrderController;
use Modules\Api\Http\Controllers\Product\ProductController;
use Modules\Api\Http\Controllers\Product\ProductMetaController;
use Modules\Api\Http\Controllers\Product\ReviewController;
use Modules\Api\Http\Controllers\Product\WarrantyController;
use Modules\Api\Http\Controllers\Product\WishListController;
use Modules\Api\Http\Controllers\SellBike\SellBikeController;
use Modules\Api\Http\Controllers\System\BannerController;
use Modules\Api\Http\Controllers\System\ColorController;
use Modules\Api\Http\Controllers\System\HomePageSectionController;
use Modules\Api\Http\Controllers\System\PrivacyPolicyController;
use Modules\Api\Http\Controllers\System\SeoSettingController;
use Modules\Api\Http\Controllers\System\ShowroomController;
use Modules\Api\Http\Controllers\System\SiteSettingController;
use Modules\Api\Http\Controllers\System\SystemAddressController;
use Modules\Api\Http\Controllers\System\TermsConditionController;
use Modules\Api\Http\Controllers\System\TestimonialController;
use Modules\Api\Http\Controllers\System\VideoReviewController;
use Modules\Api\Http\Controllers\User\UserAddressController;
use Modules\Api\Http\Controllers\User\UserController;

// Authenticating Routes
Route::controller(ApiAuthController::class)->group(function () {
    Route::match(['get', 'post'], 'login', 'login');
    Route::post('register', 'register');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});

Route::post('send-otp', [OtpController::class, 'sendOtp']); // Send OTP Routes
Route::post('verify-otp', [OtpController::class, 'verifyOtp']); // Verify OTP Routes

// System Routes (Public) or (Guest) Mode
Route::middleware('guest')->group(function () {

    Route::get('site-settings', [SiteSettingController::class, 'siteSettings']);
    Route::get('seo-settings', [SeoSettingController::class, 'seoSettings']);
                 // Banner Routes
    Route::get('testimonials', [TestimonialController::class, 'testimonials']); // Testimonial Routes
    Route::get('showrooms', [ShowroomController::class, 'showrooms']);          // Showroom Routes
    Route::get('colors', [ColorController::class, 'colors']); // Color Routes
    //    route for video review
    Route::get('video-review', [VideoReviewController::class, 'index']);

    // Routes on OrderController
    Route::controller(OrderController::class)->group(function () {
        Route::get('delivery-options', 'deliveryOptions'); // Delivery Options
        Route::get('payment-methods', 'paymentMethods');   // Payment Methods// Shipping Charges
    });
});

// User Routes (Auth) or (User) Mode
Route::middleware('auth:sanctum')->group(function () {

    Route::post("sell/store", [SellBikeController::class, 'store']);

    // Routes on user prefix
    Route::prefix('user')->group(function () {

        // Routes on user prefix
        Route::controller(UserController::class)->group(function () {
            Route::get('me', 'user');                     // User Info Routes
            Route::post('update', 'update');              // User Update Routes
        });

        // User Address List Routes
        Route::get('addresses', [UserAddressController::class, 'addresses']);  // User Address Routes
    });

    // Routes on address prefix
    Route::controller(UserAddressController::class)->prefix('address')->group(function () {
        Route::post('store', 'store');               // Address Store Routes
        Route::put('update/{id}', 'update');         // Address Update Routes
        Route::delete('delete/{id}', 'delete');      // Address Delete Routes

        //        selected address
        Route::get('selected-address/{id?}', 'getSelectedAddress');      // Address Delete Routes
    });

    //   System Address Routes
    Route::controller(SystemAddressController::class)->group(function () {
        Route::get('divisions', 'division');
        Route::get('city/{id?}', 'city');
        Route::get('area/{id?}', 'area');
    });

    //        add review
    Route::controller(ReviewController::class)->group(function () {
        Route::post('product/add-review', 'store');
    });

    //        Wishlist
    Route::controller(WishlistController::class)->prefix("wishlist")->group(function () {
        Route::post('add', 'store');
        Route::get('list', 'list');
    });

    // Routes on cart prefix
    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('/', 'carts');                      // Cart Add/Increase/Decreased Routes
        Route::post('add', 'store');               // Get Carted Products
        Route::delete('remove/{id}', 'removeCart');   // Cart Remove Routes
        Route::post('update', 'updateCart');   // Cart Update Routes
        Route::get('selected-product', 'getSelectedProduct');   // Cart Update Routes
    });

    Route::post('make-order', [OrderController::class, 'order']);
    Route::get('order-list', [OrderController::class, 'orderList']); // Make Order Routes
    Route::post('buy-now', [OrderController::class, 'buyNow']); // Buy Now Routes
    Route::post('buy-now/make-order', [OrderController::class, 'makeOrderFromBuyNow']); // Buy Now Routes
});

// Product Routes (Auth) or (Guest) Mode
Route::middleware('guest')->group(function () {

//   product meta
    Route::controller(ProductMetaController::class)->prefix('product-meta')->group(function () {
        Route::get('category/{slug}', 'productMeta');
    });


//    Route on Banner
    Route::controller(BannerController::class)->prefix('banners')->group(function () {
        Route::get('/', 'banners');
        Route::get('category/{id}', 'getBannerByCategory');
        Route::get('product/{id}', 'getBannerByProduct');
    });



    // brands route
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
        Route::get('/popular', [BrandController::class, 'popularBrands']);
        Route::get('/category/{slug}', [BrandController::class, 'categoryBrands']);
    });
    //Routes on Product Category
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'categories');                // Product Categories
        Route::get('popular-categories', 'popularCategories'); // Product Popular Categories
    });
    //    Routes on Pre-Order
    Route::controller(PreOrderController::class)->prefix('pre-order')->group(function () {
        Route::post('/store', 'store');
    });
    // Routes on product prefix
    Route::prefix('product')->group(function () {
        // Route for product count
        Route::get('counts', [ProductController::class, 'totalProductType']);          // Total Product Count
        Route::get('review/{id}', [ReviewController::class, 'review']); // Product Review
    });
    //        Route on Terms and Condition
    Route::controller(TermsConditionController::class)->group(function () {
        Route::get('terms', 'terms');
    });
    //        Route on Privacy Policy
    Route::controller(PrivacyPolicyController::class)->group(function () {
        Route::get('privacy-policy', 'privacyPolicy');
    });

    //        Sell Bike
    Route::controller(SellBikeController::class)->prefix('sell')->group(function () {
        Route::get('bike/{brand_id}', 'bikeByBrand');
    });

    Route::get('shipping-charges/{name?}', [ShippingChargeController::class, 'shippingCharges']);
});
//Route::get('bike/details/{name}', [BikeController::class, 'details']);
// Routes on feature prefix
Route::middleware('product')->group(function () {
    Route::controller(ProductController::class)->prefix('featured')->group(function () {
        Route::get('/product/{id}', 'getFeaturedProduct');   // Feature new bikes
    });
    Route::controller(HomePageSectionController::class)->prefix('new-arrivals')->group(function () {
        Route::get('/', 'homePageSections');   // feature new arrivals
        Route::get('featured', 'featured');   // feature new arrivals
    });
});
