<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('landing');


Route::get('/customer/about', fn() => view('customer.about'))->name('customer.about');

// Monitoring & Health Check Routes
Route::controller(MetricsController::class)->group(function () {
    Route::get('/metrics', 'metrics')->name('metrics')->withoutMiddleware('web')->name('metrics');
    Route::get('/health', 'health')->name('health');
    Route::get('/ready', 'ready')->name('ready');
    Route::get('/alive', 'alive')->name('alive');
});

// Authentication (Guest Only)
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('/forgot-password', 'showForm')->name('password.request');
        Route::post('/forgot-password', 'sendResetLink')->name('password.email');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
        Route::post('/reset-password', 'updatePassword')->name('password.update');
    });
});


// Google OAuth
Route::get('/auth-google-redirect', [AuthController::class, 'google_redirect']);
Route::get('/auth-google-callback', [AuthController::class, 'google_callback']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Customer Auth Routes
Route::middleware(['auth', 'check_role:customer,admin'])->group(function () {

    //Home & Products
    Route::prefix('customer')->controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('customer.home');
        Route::get('/products', 'products')->name('customer.products');
    });

    Route::get('/about', fn() => view('customer.about'))->name('customer.about');

    // Product Detail
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('customer.product.detail');
});

// Order Routes
Route::middleware('auth')->prefix('orders')->controller(OrderController::class)->group(function () {

    // Order List
    Route::get('/', 'index')->name('orders.index');

    // Order Detail
    Route::get('/{order}', 'show')->name('orders.show');

    // Order Directly
    Route::get('/direct/{product}', 'createDirect')->name('orders.direct.create');
    Route::post('/direct/{product}', 'storeDirect')->name('orders.direct.store');

    // Order from cart
    Route::get('/from-cart', 'createFromCart')->name('orders.cart.create');
    Route::post('/from-cart', 'storeFromCart')->name('orders.cart.store');

});


// Cart Routes
Route::middleware('auth')->prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/add/{product}', 'add')->name('cart.add');
    Route::post('update/{cartItem}', 'update')->name('cart.update');
    Route::delete('remove/{cartItem}', 'remove')->name('cart.remove');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

