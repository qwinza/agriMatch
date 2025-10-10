<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;

// Halaman utama
Route::get('/', [ProductController::class, 'index'])->name('home');

// Dashboard berdasarkan peran
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard Petani
    Route::get('/dashboard/petani', [DashboardController::class, 'petani'])
        ->middleware('role:petani')
        ->name('farmers.dashboard');

    // Dashboard Pembeli
    Route::get('/dashboard/pembeli', [DashboardController::class, 'pembeli'])
        ->middleware('role:pembeli')
        ->name('buyers.dashboard');

    // Produk (CRUD, hanya user login)
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    // Pemesanan produk
    Route::post('products/{product}/order', [OrderController::class, 'store'])->name('products.order');

    // Melihat daftar pesanan
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    // Memberi ulasan pada produk
    Route::post('products/{product}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');
});

require __DIR__ . '/auth.php';
