<?php

use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\Auth\ApiAuthController;
use Modules\Api\Http\Controllers\Cart\GuestCartController;
use Modules\Api\Http\Controllers\Dynamic\DynamicPageController;
use Modules\Api\Http\Controllers\Guest\GuestOrderController;
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
use Modules\Api\Http\Controllers\System\AboutController;
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
use Illuminate\Support\Facades\Mail;

// Authenticating Routes
Route::controller(ApiAuthController::class)->group(function () {
    Route::match(['get', 'post'], 'login', 'login');
    Route::post('register', 'register');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
    //    social login
    Route::post('google-login', 'googleLogin');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});

Route::post('send-otp', [OtpController::class, 'sendOtp'])->name('send-otp'); // Send OTP Routes
Route::post('verify-otp', [OtpController::class, 'verifyOtp']);               // Verify OTP Routes

// System Routes (Public) or (Guest) Mode
Route::middleware('guest')->group(function () {
    Route::get('site-settings', [SiteSettingController::class, 'siteSettings']); // -------------- cached
    Route::get('seo-settings', [SeoSettingController::class, 'seoSettings']);    // -------------- cached
    Route::get('about', [AboutController::class, 'index']);                      // -------------- cached
    // Banner Routes
    Route::get('testimonials', [TestimonialController::class, 'testimonials']);  // Testimonial Routes // -------------- cached
    Route::get('showrooms', [ShowroomController::class, 'showrooms']);           // Showroom Routes // -------------- cached
    Route::get('colors', [ColorController::class, 'colors']);                    // Color Routes // -------------- cached
    //    route for video review
    Route::get('video-review', [VideoReviewController::class, 'index']);         // -------------- cached
    // Routes on OrderController
    Route::controller(OrderController::class)->group(function () {
        Route::get('delivery-options', 'deliveryOptions'); // Delivery Options // -------------- cached
        Route::get('payment-methods', 'paymentMethods');   // Payment Methods// Shipping Charges // -------------- cached
    });
});

// User Routes (Auth) or (User) Mode
Route::middleware('auth:sanctum')->group(function () {

    // Routes on user prefix
    Route::prefix('user')->group(function () {
        // Routes on user prefix
        Route::controller(UserController::class)->group(function () {
            Route::get('me', 'user');                     // User Info Routes
            Route::post('update', 'update');
            Route::post('change-password', 'changePassword');
        });;  // User Address Routes
    });

    // Routes on address prefix
    Route::controller(UserAddressController::class)->prefix('address')->group(function () {
        Route::get('/', 'addresses');                // Address List Routes
        Route::post('store', 'store');
        Route::get('edit/{id}', 'edit');                            // Address Store Routes
        Route::post('update/{id}', 'update');                       // Address Update Routes
        Route::delete('delete/{id}', 'delete');                     // Address Delete Routes
        Route::get('selected-address/', 'getSelectedAddress');      // Address Delete Routes
    });

    //        add review
    Route::controller(ReviewController::class)->group(function () {
        Route::post('product/add-review', 'store');
    });

    //        Wishlist
    Route::controller(WishlistController::class)->prefix("wishlist")->group(function () {
        Route::post('add', 'store');
        Route::get('list', 'list');
        Route::delete('remove/{id}', 'delete');
    });

    // Routes on cart prefix
    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('/', 'carts');                               // Cart Add/Increase/Decreased Routes
        Route::post('add', 'store');                            // Get Carted Products
        Route::delete('remove/{id}', 'removeCart');             // Cart Remove Routes
        Route::post('update', 'updateCart');                    // Cart Update Routes
        Route::get('selected-product', 'getSelectedProduct');   // Cart Update Routes
    });

    Route::post('make-order', [OrderController::class, 'order']);
    Route::get('order-list', [OrderController::class, 'orderList']);                    // Make Order Routes
    Route::post('buy-now', [OrderController::class, 'buyNow']);                         // Buy Now Routes
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
        Route::get('home-slider', 'homeSlider');
    });

    // brands route
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
        Route::get('/popular', [BrandController::class, 'popularBrands']);
        Route::get('/category/{slug}', [BrandController::class, 'categoryBrands']);
    });

    //Routes on Product Category
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'categories');                         // Product Categories
        Route::get('popular-categories', 'popularCategories'); // Product Popular Categories
        Route::get('/subcategory', 'subCategories');           // Product Sub Categories
    });

    //    Routes on Pre-Order
    Route::controller(PreOrderController::class)->prefix('pre-order')->group(function () {
        Route::post('/store', 'store');
    });

    // Routes on product prefix
    Route::prefix('product')->group(function () {
        // Route for product count
        Route::get('counts', [ProductController::class, 'totalProductType']);          // Total Product Count
        Route::get('review/{id}', [ReviewController::class, 'review']);                // Product Review
        Route::get('related/{id}', [ProductController::class, 'relatedProduct']);      // Related Product
    });

    //        Route on Terms and Condition
    Route::controller(TermsConditionController::class)->group(function () {
        Route::get('terms', 'terms');   // -------------- cached
    });

    //        Route on Privacy Policy
    Route::controller(PrivacyPolicyController::class)->group(function () {
        Route::get('privacy-policy', 'privacyPolicy'); // -------------- cached
    });

    //        Sell Bike
    //    Route::controller(SellBikeController::class)->prefix('sell')->group(function () {
    //        Route::get('bike/{brand_id}', 'bikeByBrand');
    //    });


});
//Route::get('bike/details/{name}', [BikeController::class, 'details']);
// Routes on feature prefix
Route::middleware('product')->group(function () {
    Route::controller(ProductController::class)->prefix('product')->group(function () {
        Route::get('/featured/{id}', 'getFeaturedProduct');   // Feature product
        Route::get('/', 'getProduct');                        // Feature product // -------------- cached
        Route::get('details/{name}', 'details');              // Product Details // -------------- cached
        Route::get('get-data/{id}', 'getProductDataById');
        Route::post('calculate_product_price', 'calculatePrice');
        Route::get('related', 'relatedProduct');                                   // Related Product // -------------- cached
        Route::get('total-review/{id}', [ReviewController::class, 'totalReview']); // Product Review
        Route::get('get-product-by-brand/{slug}', 'getProductByBrand');            // Product Review
        Route::get('/new-arrivals', 'newArrivals');                                // Product Review
        Route::get('featured-new-arrivals', 'featuredNewArrivals');                // Product Review
    });

    Route::controller(HomePageSectionController::class)->prefix('dynamic-section')->group(function () {
        Route::get('/', 'homePageSections');   // feature new arrivals
    });

    Route::get('promotional-products', [DynamicPageController::class, 'allPromotionalProduct']);
});

// dynamic page api
Route::get('dynamic-page/{slug}', [DynamicPageController::class, 'allBrandProduct']);

//Search Suggestions
Route::get('search-suggestions/{name}', [ProductController::class, 'searchSuggestions']);

// guest cart
Route::prefix('guest-cart')->middleware('api-session')->as('guest-cart.')->controller(GuestCartController::class)->group(function () {
    Route::post('add', 'store')->name('add');
    Route::get('list/{id}', 'getCartProduct')->name('list');
    Route::Post('/delete', 'removeProductFromCart')->name('remove');
    Route::post('update', 'updateCart')->name('update');
    Route::get('selected-product/{id}', 'getSelectedCartProduct')->name('selected-product');
});

Route::prefix('guest-order')->middleware('api-session')->as('guest-order.')->controller(GuestOrderController::class)->group(function () {
    Route::post('buy-now', 'guestOrder')->name('buy-now');
});

Route::controller(SystemAddressController::class)->group(function () {
    Route::get('divisions', 'division'); // -------------- cached
    Route::get('city/{id?}', 'city');    // -------------- cached
    Route::get('area/{id?}', 'area');    // -------------- cached
});

Route::get('shipping-charges/{name?}', [ShippingChargeController::class, 'shippingCharges']);

Route::post('create-guest-user', [GuestCartController::class, 'createGuestUser']);
Route::get('voucher-discount', [OrderController::class, 'getVoucherDiscount']); // Buy Now Routes


//Route::get('order-cart',function (){
//   $cart = \App\Models\Order\Cart::pluck('id')->toArray();
//   return $cart;
//});
