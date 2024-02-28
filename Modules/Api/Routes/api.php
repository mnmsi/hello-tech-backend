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
    Route::match(['get', 'post'], 'login', 'login'); //------ documented
    Route::post('register', 'register'); //------ documented
    Route::post('forgot-password', 'forgotPassword'); //------ documented
    Route::post('reset-password', 'resetPassword'); //------ documented
    //    social login
    Route::post('google-login', 'googleLogin'); //------ documented
    Route::get('logout', 'logout')->middleware('auth:sanctum'); //------ documented
});

Route::post('send-otp', [OtpController::class, 'sendOtp'])->name('send-otp'); // Send OTP Routes //------ documented
Route::post('verify-otp', [OtpController::class, 'verifyOtp']);               // Verify OTP Routes //------ documented

// System Routes (Public) or (Guest) Mode
Route::middleware('guest')->group(function () {
    Route::get('site-settings', [SiteSettingController::class, 'siteSettings']); // -------------- cached [documented]
    Route::get('seo-settings', [SeoSettingController::class, 'seoSettings']);    // -------------- cached [documented]
    Route::get('about', [AboutController::class, 'index']);                      // -------------- cached [documented]
    // Banner Routes
    Route::get('testimonials', [TestimonialController::class, 'testimonials']);  // Testimonial Routes // -------------- cached [documented]
//    Route::get('colors', [ColorController::class, 'colors']);                    // Color Routes // -------------- cached [documented]
    //    route for video review
    Route::get('video-review', [VideoReviewController::class, 'index']);         // -------------- cached [documented]
    // Routes on OrderController
    Route::controller(OrderController::class)->group(function () {
        Route::get('delivery-options', 'deliveryOptions'); // Delivery Options // -------------- cached [documented]
        Route::get('payment-methods', 'paymentMethods');   // Payment Methods// Shipping Charges // -------------- cached [documented]
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
    })->middleware('auth:sanctum');

    // Routes on address prefix
    Route::controller(UserAddressController::class)->prefix('address')->group(function () {
        Route::get('/', 'addresses');                // Address List Routes [documented]
        Route::post('store', 'store');
        Route::get('edit/{id}', 'edit');                            // Address Store Routes [documented]
        Route::post('update/{id}', 'update');                       // Address Update Routes [documented]
        Route::delete('delete/{id}', 'delete');                     // Address Delete Routes [documented]
        Route::get('selected-address/', 'getSelectedAddress');      // Address Delete Routes [documented]
    });

    //        add review
    Route::controller(ReviewController::class)->group(function () {
        Route::post('product/add-review', 'store'); // Add Review Routes [documented]
    });

    //        Wishlist
    Route::controller(WishlistController::class)->prefix("wishlist")->group(function () {
        Route::post('add', 'store'); // Add Wishlist Routes [documented]
        Route::get('list', 'list'); // Get Wishlist Routes [documented]
        Route::delete('remove/{id}', 'delete'); // Remove Wishlist Routes [documented]
    });

    // Routes on cart prefix
    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('/', 'carts');                               // Cart Add/Increase/Decreased Routes [documented]
        Route::post('add', 'store');                            // Get Carted Products Routes [documented]
        Route::delete('remove/{id}', 'removeCart');             // Cart Remove Routes [documented]
        Route::post('update', 'updateCart');                    // Cart Update Routes [documented]
        Route::get('selected-product', 'getSelectedProduct');   // Cart Update Routes [documented]
    });

    Route::post('make-order', [OrderController::class, 'order']); // Make Order Routes [documented]
    Route::get('order-list', [OrderController::class, 'orderList']); // Order List Routes [documented]

});

// Product Routes (Auth) or (Guest) Mode
Route::middleware('guest')->group(function () {

    //   product meta
    Route::controller(ProductMetaController::class)->prefix('product-meta')->group(function () {
        Route::get('category/{slug}', 'productMeta'); // Product Meta Routes // -------------- [documented]
    });

    //    Route on Banner
    Route::controller(BannerController::class)->prefix('banners')->group(function () {
        Route::get('/', 'banners');         // -------------- cached
        Route::get('category/{id}', 'getBannerByCategory');  // -------------- cached
        Route::get('product/{id}', 'getBannerByProduct');  // -------------- cached
        Route::get('home-slider', 'homeSlider');  // -------------- cached
    });

    // brands route
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']); //---------------- Cached
        Route::get('/popular', [BrandController::class, 'popularBrands']); //--------Cached
        Route::get('/category/{slug}', [BrandController::class, 'categoryBrands']); //--------Cached
    });

    //Routes on Product Category
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'categories');                         // Product Categories // -------------- cached [documented]
        Route::get('popular-categories', 'popularCategories'); // Product Popular Categories // -------------- cached [documented]
        Route::get('/subcategory', 'subCategories');           // Product Sub Categories // -------------- cached [documented]
    });

    //    Routes on Pre-Order
    Route::controller(PreOrderController::class)->prefix('pre-order')->group(function () {
        Route::post('/store', 'store');  // Pre-Order Store Routes [documented]
    });

    // Routes on product prefix
    Route::prefix('product')->group(function () {
        // Route for product count
        Route::get('counts', [ProductController::class, 'totalProductType']);          // Total Product Count
        Route::get('review/{id}', [ReviewController::class, 'review']);                // Product Review [documented]
        Route::get('related/{id}', [ProductController::class, 'relatedProduct']);      // Related Product // -------------- cached [documented]
    });

    //        Route on Terms and Condition
    Route::controller(TermsConditionController::class)->group(function () {
        Route::get('terms', 'terms');   // -------------- cached [documented]
    });

    //        Route on Privacy Policy
    Route::controller(PrivacyPolicyController::class)->group(function () {
        Route::get('privacy-policy', 'privacyPolicy'); // -------------- cached [documented]
    });



});
// Routes on feature prefix
Route::middleware('product')->group(function () {
    Route::controller(ProductController::class)->prefix('product')->group(function () {
        Route::get('/featured/{id}', 'getFeaturedProduct');   // Feature product [documented]
        Route::get('/', 'getProduct');                        // Feature product // -------------- cached [documented]
        Route::get('details/{name}', 'details');              // Product Details // -------------- cached [documented]
        Route::get('get-data/{id}', 'getProductDataById');
        Route::post('calculate_product_price', 'calculatePrice');  // Product Price Calculation [documented]
        Route::get('related', 'relatedProduct');                                   // Related Product // -------------- cached [documented]
        Route::get('total-review/{id}', [ReviewController::class, 'totalReview']); // Product Review [documented]
        Route::get('get-product-by-brand/{slug}', 'getProductByBrand');            // Product Review [documented]
        Route::get('/new-arrivals', 'newArrivals');                                // Product Review // -------------- cached [documented]
        Route::get('featured-new-arrivals', 'featuredNewArrivals');                // Product Review // -------------- cached [documented]
    });

    Route::controller(HomePageSectionController::class)->prefix('dynamic-section')->group(function () {
        Route::get('/', 'homePageSections');   // feature new arrivals [documented]
    });

    Route::get('promotional-products', [DynamicPageController::class, 'allPromotionalProduct']); // Promotional Product // -------------- cached [documented]
});

// dynamic page api
Route::get('dynamic-page/{slug}', [DynamicPageController::class, 'allBrandProduct']);  // [documented]

//Search Suggestions
Route::get('search-suggestions/{name}', [ProductController::class, 'searchSuggestions']); // [documented]

// guest cart
Route::prefix('guest-cart')->middleware('api-session')->as('guest-cart.')->controller(GuestCartController::class)->group(function () {
    Route::post('add', 'store')->name('add');  // Add Product to Cart [documented]
    Route::get('list/{id}', 'getCartProduct')->name('list'); // Get Carted Products [documented]
    Route::Post('/delete', 'removeProductFromCart')->name('remove'); // Remove Product from Cart [documented]
    Route::post('update', 'updateCart')->name('update'); // Update Carted Products [documented]
    Route::get('selected-product/{id}', 'getSelectedCartProduct')->name('selected-product'); // Get Selected Carted Products [documented]
});

Route::prefix('guest-order')->middleware('api-session')->as('guest-order.')->controller(GuestOrderController::class)->group(function () {
    Route::post('buy-now', 'guestOrder')->name('buy-now');
});

Route::controller(SystemAddressController::class)->group(function () {
    Route::get('divisions', 'division'); // -------------- cached
    Route::get('city/{id?}', 'city');    // -------------- cached
    Route::get('area/{id?}', 'area');    // -------------- cached
});

Route::get('shipping-charges/{name?}', [ShippingChargeController::class, 'shippingCharges']); //.............. Cached

Route::post('create-guest-user', [GuestCartController::class, 'createGuestUser']); // Create Guest User [documented]
Route::get('voucher-discount', [OrderController::class, 'getVoucherDiscount']); // Get Voucher Discount


//Route::get('order-cart',function (){
//   $cart = \App\Models\Order\Cart::pluck('id')->toArray();
//   return $cart;
//});
