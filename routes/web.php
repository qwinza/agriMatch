<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CheckoutController;


// =====================
// 🏠 HALAMAN UTAMA
// =====================
Route::get('/', function () {
    return view('dashboard');
})->name('home');

// =====================
// 🔐 AUTENTIKASI
// =====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

// Role Selection
Route::middleware('auth')->group(function () {
    Route::get('/choose-role', [AuthController::class, 'showChooseRole'])->name('choose-role');
    Route::post('/choose-role', [AuthController::class, 'saveRole']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =====================
// 🧭 DASHBOARD & AUTH ROUTES
// =====================
Route::middleware(['auth'])->group(function () {

    // Dashboard utama - redirect berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard Petani
    Route::get('/farmers/dashboard', [DashboardController::class, 'petani'])->name('farmers.dashboard');

    // Dashboard Pembeli
    Route::get('/buyers/dashboard', [DashboardController::class, 'pembeli'])->name('buyers.dashboard');

    // =====================
    // 🛒 PRODUCT ROUTES - FIXED ORDER
    // =====================

    // Routes TANPA parameter harus di ATAS
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/my-products', [ProductController::class, 'myProducts'])->name('products.my-products');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Routes DENGAN parameter harus di BAWAH
    Route::get('/products/{encryptedId}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{encryptedId}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{encryptedId}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{encryptedId}', [ProductController::class, 'destroy'])->name('products.destroy');

    // =====================
    // 📦 ORDER ROUTES
    // =====================
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // =====================
    // ⭐ REVIEW ROUTES
    // =====================
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');

    // =====================
    // 📊 REPORT ROUTES
    // =====================
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // routes/web.php - Tambahkan dalam group buyer
    Route::prefix('buyer')->name('buyer.')->middleware(['check.role:pembeli'])->group(function () {

        // Marketplace - lihat semua produk
        Route::get('/marketplace', [BuyerController::class, 'marketplace'])->name('marketplace');
        Route::get('/products/{encryptedId}', [BuyerController::class, 'productDetail'])->name('products.show');

        // Cart System
        Route::get('/cart', [BuyerController::class, 'cart'])->name('cart');
        Route::post('/cart/add', [BuyerController::class, 'addToCart'])->name('cart.add');
        Route::put('/cart/update/{cartItem}', [BuyerController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove/{cartItem}', [BuyerController::class, 'removeFromCart'])->name('cart.remove');

        // Checkout & Payment
        Route::post('/checkout', [BuyerController::class, 'checkout'])->name('checkout');
        Route::get('/checkout/success', [BuyerController::class, 'checkoutSuccess'])->name('checkout.success');
        Route::get('/checkout/failed', [BuyerController::class, 'checkoutFailed'])->name('checkout.failed');
    });


    Route::middleware('auth')->group(function () {
        Route::get('/products/{encryptedId}/buy', [TransaksiController::class, 'create'])->name('transactions.create');
        Route::post('/transactions/pay', [TransaksiController::class, 'pay'])->name('transactions.pay');
        Route::get('/transactions/finish', [TransaksiController::class, 'finish'])->name('transactions.finish');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    });

    Route::get('/checkout/{order}', [CheckoutController::class, 'show'])->name('checkout.show')->middleware('auth');

    // Webhook Midtrans
    Route::post('/checkout/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');





});