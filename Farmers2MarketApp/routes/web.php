<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InquiryController;

// ------------------
// Public Pages
// ------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [InquiryController::class, 'submitForm'])->name('contact.submit');

// ------------------
// Marketplace
// ------------------
Route::get('/market', [MarketController::class, 'index'])->name('market');
Route::get('/analysis', [MarketController::class, 'index'])->name('analysis');

// ------------------
// Authentication
// ------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ------------------
// Buyer Dashboard (Authenticated)
// ------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');
    Route::post('/buyer/address/update', [BuyerDashboardController::class, 'updateAddress'])->name('buyer.update.address');
});

// ------------------
// Cart & Order Routes
// ------------------
// Add item to cart (session)
Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');

// Checkout / order detail page
Route::match(['get','post'], '/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');


// Place order (COD or PayHere)
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');

// PayHere callbacks
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');
Route::get('/order/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
Route::post('/order/notify', [OrderController::class, 'notify'])->name('order.notify');
