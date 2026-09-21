<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SmsInboxController;
use App\Http\Controllers\SmsWebhookController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// ═══ Storefront ═══
Route::get('/', [StorefrontController::class, 'index']);
Route::get('/shop', [StorefrontController::class, 'index']);

Route::get('/product/{id}', [StorefrontController::class, 'product']);
Route::post('/product/{id}/review', [ReviewController::class, 'store']);

Route::get('/cart', [StorefrontController::class, 'cart']);
Route::post('/cart/add/{id}', [StorefrontController::class, 'addToCart']);
Route::post('/cart/update/{id}', [StorefrontController::class, 'updateCart']);
Route::post('/cart/remove/{id}', [StorefrontController::class, 'removeFromCart']);

Route::get('/wishlist', [WishlistController::class, 'index']);
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle']);

Route::get('/checkout', [StorefrontController::class, 'checkout']);
Route::post('/checkout', [StorefrontController::class, 'placeOrder']);
Route::get('/order-success/{id}', [StorefrontController::class, 'orderSuccess']);
Route::post('/track-order', [StorefrontController::class, 'trackOrder'])->name('track.order');

// ═══ Auth ═══
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// ═══ Dashboard ═══
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/delete-image', [ProductController::class, 'deleteImage']);
    Route::resource('categories', CategoryController::class);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::get('sms', [SmsInboxController::class, 'index']);
    Route::get('sms/{sms}', [SmsInboxController::class, 'show']);
    Route::post('sms/{sms}/confirm', [SmsInboxController::class, 'confirm']);
    Route::post('sms/{sms}/reject', [SmsInboxController::class, 'reject']);
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('settings', [SettingsController::class, 'index']);
    Route::post('settings', [SettingsController::class, 'update']);
});

// ═══ Webhook ═══
Route::post('/webhooks/sms/{token}', [SmsWebhookController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\ResolveTenant::class]);
