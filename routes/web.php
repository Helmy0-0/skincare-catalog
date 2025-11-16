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
Route::get('/', [HomeController::class, 'index'])->name('customer.home');

// Monitoring & Health Check Routes
Route::controller(MetricsController::class)->group(function () {
    Route::get('/metrics', 'metrics')->name('metrics')->withoutMiddleware('web');
    Route::get('/health', 'health')->name('health');
    Route::get('/ready', 'ready')->name('ready');
    Route::get('/alive', 'alive')->name('alive');
});

// Authentication
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



// Protected Routes (Authenticated - Customer)
Route::middleware(['auth', 'check_role:customer'])->group(function () {
    Route::prefix('customer')->controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('customer.home');
        Route::get('/products', 'products')->name('customer.products');
    });
});

// Order Routes
Route::middleware('auth')->prefix('order')->controller(OrderController::class)->group(function () {
    Route::get('/direct/{product}', 'createDirect')->name('orders.create-direct');
    Route::post('/direct/{product}', 'storeDirect')->name('orders.store-direct');
    Route::get('/{order}', 'show')->name('orders.show');

    Route::get('/order/from-cart', [OrderController::class, 'createFromCart'])
        ->name('orders.create-from-cart');
    Route::post('/order/from-cart', [OrderController::class, 'storeFromCart'])
        ->name('orders.store-from-cart');

    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
});

// Cart Routes
Route::middleware('auth')->prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/add/{product}', 'add')->name('cart.add');
    Route::post('update/{cartItem}', 'update')->name('cart.update');
    Route::delete('remove/{cartItem}', 'remove')->name('cart.remove');
    // Route::delete('clear', 'clear')->name('cart.clear');
});


// Route::get('/cart', [CartController::class,   'index'])->name('cart.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/product/{id}', [ProductController::class, 'show'])->name('customer.product.detail');
