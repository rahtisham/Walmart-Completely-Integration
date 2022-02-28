<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PDFConotroller;
use App\Http\Controllers\AuthorizeNetController;;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Walmart\Alerts\RatingRaviewController;
use App\Http\Controllers\Walmart\Alerts\OnTimeDeliveryController;
use App\Http\Controllers\Walmart\Alerts\OnTimeShipmentController;
use App\Http\Controllers\Walmart\Alerts\CarrierPerformanceController;
use App\Http\Controllers\Walmart\Alerts\RegionalPerformanceController;
use App\Http\Controllers\Walmart\Alerts\OrdersContnroller;
use App\Http\Controllers\Walmart\Alerts\ShippingPerformanceController;
use App\Http\Controllers\Walmart\Alerts\OrderStatusCheckController;
use App\Http\Controllers\Walmart\Alerts\ItemsController;
use App\Http\Controllers\MarketPlace\MarketPlaceController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
Route::get('/', [MarketPlaceController::class, 'home'])->name('home');


Route::get('registration-form', function () {
    return view('payment');
});

Route::get('/register', [CheckoutController::class, 'login'])->name('register');
Route::get('/checkout/{subscribtion}', [CheckoutController::class, 'index'])->name('checkouts');
Route::post('/create', [CheckoutController::class, 'create'])->name('create');

Route::group(['middleware' => 'auth'] , function(){


    Route::prefix('dashboard')->group(function () {

        Route::prefix('marketplace')->group(function () {

            Route::get('/', [MarketPlaceController::class, 'index'])->name('dashboard.marketplace');
            Route::get('/plate-form', [MarketPlaceController::class, 'plateForm'])->name('dashboard.select-marketplace');
            Route::get('walmart', [MarketPlaceController::class, 'walmartRegister'])->name('dashboard.select-marketplace.register');
            Route::post('walmart/add', [MarketPlaceController::class, 'walmartIntegration'])->name('dashboard.marketplace.walmart.integration');
            Route::get('/thank-you', [MarketPlaceController::class, 'thankYouPage'])->name('thank-you');
        });


        Route::get('/pdf' , [PDFConotroller::class , 'index'])->name('dashboard.pdf');
        Route::get('generatepdf', [PDFConotroller::class, 'generatePDF'])->name('dashboard.generatepdf');



        Route::get('shipping-performance', [ShippingPerformanceController::class, 'index'])->name('dashboard.shipping-performance');
        Route::post('shipping-performance-add', [ShippingPerformanceController::class, 'ratingReview'])->name('dashboard.shipping-performance-add');


        Route::get('rating-review', [RatingRaviewController::class, 'index'])->name('dashboard.rating-review');
        Route::post('rating-review-add', [RatingRaviewController::class, 'ratingReview'])->name('dashboard.rating-review-add');



        Route::get('on-time-delivery', [OnTimeDeliveryController::class, 'index'])->name('dashboard.on-time-delivery');
        Route::get('on-time-delivery-add', [OnTimeDeliveryController::class, 'OnTimeDelivered'])->name('dashboard.on-time-delivery-add');



        Route::get('on-time-shipment', [OnTimeShipmentController::class, 'index'])->name('dashboard.on-time-shipment');
        Route::get('on-time-shipment-add', [OnTimeShipmentController::class, 'OnTimeShipment'])->name('dashboard.on-time-shipment-add');



        Route::get('carrier-performance', [CarrierPerformanceController::class, 'index'])->name('dashboard.carrier-performance');
        Route::get('carrier-performance-add', [CarrierPerformanceController::class, 'carrierPerformance'])->name('dashboard.carrier-performance-add');



        Route::get('regional-performance', [RegionalPerformanceController::class, 'index'])->name('dashboard.regional-performance');
        Route::get('regional-performance-add', [RegionalPerformanceController::class, 'regionalPerformance'])->name('dashboard.regional-performance-add');



        Route::get('order', [OrdersContnroller::class, 'index'])->name('dashboard.order');
        Route::post('order-add', [OrdersContnroller::class, 'orderDetails'])->name('dashboard.order-add');

        Route::get('order-status', [OrderStatusCheckController::class, 'index'])->name('dashboard.order-status');
        Route::post('order-status-check', [OrderStatusCheckController::class, 'orderStatusCheck'])->name('dashboard.order-status-check');


        Route::get('items', [ItemsController::class, 'index'])->name('dashboard.items');
        Route::post('items-add', [ItemsController::class, 'walmartItems'])->name('dashboard.items-add');


        Route::get('client', [DashboardController::class, 'client'])->name('dashboard.client');


        Route::get('authorize', [AuthorizeNetController::class, 'index'])->name('dashboard.authorize');


        Route::get('pay' , [PaymentController::class , 'pay'])->name('pay');
        Route::post('/dopay/online' , [PaymentController::class , 'handleonlinepay'])->name('dopay.online');

    });

});


//
//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->name('dashboard');
