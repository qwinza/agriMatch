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

    // =====================
    // 🛍️ BUYER ROUTES
    // =====================
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

    // =====================
    // 💳 TRANSACTION ROUTES - FIXED VERSION
    // =====================
    Route::prefix('transactions')->name('transactions.')->group(function () {
        // Transaction creation and payment
        Route::get('/create/{encryptedId}', [TransaksiController::class, 'create'])->name('create');
        Route::post('/pay', [TransaksiController::class, 'pay'])->name('pay');
        Route::post('/pay-auto-success', [TransaksiController::class, 'payAutoSuccess'])->name('pay.auto-success');
        Route::get('/finish', [TransaksiController::class, 'finish'])->name('finish');

        // Order management
        Route::get('/my-orders', [TransaksiController::class, 'myOrders'])->name('my-orders');
        Route::get('/order-detail/{id}', [TransaksiController::class, 'orderDetail'])->name('order-detail');

        // Payment status checking
        Route::get('/check-payment-status/{orderCode}', [TransaksiController::class, 'checkPaymentStatus'])->name('check-payment-status');
        Route::get('/sync-payment-status/{orderCode}', [TransaksiController::class, 'syncPaymentStatus'])->name('sync-payment-status');
    });

    // =====================
    // 🔔 NOTIFICATION ROUTES
    // =====================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
    });

    // =====================
    // 🛒 CHECKOUT ROUTES
    // =====================
    Route::get('/checkout/{order}', [CheckoutController::class, 'show'])->name('checkout.show');

    // =====================
    // 🔄 CALLBACK ROUTES (TANPA AUTH KARENA DIPANGGIL OLEH MIDTRANS)
    // =====================
    Route::post('/payment/callback', [TransaksiController::class, 'callback'])->name('payment.callback');
    Route::post('/checkout/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');

    // =====================
    // 🧪 TESTING & DEBUG ROUTES
    // =====================
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/callback', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'Callback URL is accessible',
                'callback_url' => url('/payment/callback'),
                'timestamp' => now()->toDateTimeString(),
                'environment' => app()->environment()
            ]);
        })->name('callback');

        Route::get('/callback-post', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'POST callback URL is accessible',
                'method' => 'POST'
            ]);
        })->name('callback-post');

        Route::get('/ngrok-info', function () {
            $ngrokUrl = \App\Helpers\NgrokHelper::getNgrokUrl();

            return response()->json([
                'ngrok_running' => !is_null($ngrokUrl),
                'ngrok_url' => $ngrokUrl,
                'app_url' => config('app.url'),
                'environment' => app()->environment(),
                'callback_url' => $ngrokUrl ? $ngrokUrl . '/payment/callback' : null,
                'finish_url' => $ngrokUrl ? $ngrokUrl . '/transactions/finish' : null,
                'server_time' => now()->toDateTimeString()
            ]);
        })->name('ngrok-info');

        Route::get('/callback-manual', function () {
            // Simulate callback data
            $testData = [
                'order_id' => 'TRX-1234567890-ABC123',
                'transaction_status' => 'settlement',
                'status_code' => '200',
                'gross_amount' => '50000',
                'signature_key' => 'test_signature',
                'payment_type' => 'credit_card',
                'transaction_id' => 'test_transaction_123'
            ];

            return response()->json([
                'callback_test' => 'Manual test',
                'expected_url' => url('/payment/callback'),
                'test_data' => $testData
            ]);
        })->name('callback-manual');

        // Debug route untuk order
        Route::get('/debug/order/{id}', [TransaksiController::class, 'debugOrder'])->name('debug-order');
    });

});

// =====================
// 🌐 PUBLIC ROUTES (TANPA AUTH)
// =====================
Route::get('/products/public/{encryptedId}', [ProductController::class, 'showPublic'])->name('products.public.show');

// =====================
// 🚨 FALLBACK ROUTE
// =====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});