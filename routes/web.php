<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// =====================
// 🏠 HALAMAN UTAMA (Guest)
// =====================
Route::get('/', function () {
    return view('dashboard'); // view: resources/views/dashboard.blade.php
})->name('home');

// =====================
// 🔐 AUTENTIKASI (AuthController tunggal)
// =====================

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/choose-role', [AuthController::class, 'showChooseRole']);
Route::post('/choose-role', [AuthController::class, 'saveRole']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =====================
// 🧭 DASHBOARD BERDASARKAN ROLE
// =====================
Route::middleware(['auth'])->group(function () {

    // Dashboard utama (cek role user)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard Petani
    Route::get('/farmers/dashboard', [DashboardController::class, 'petani'])
        ->middleware('role:petani')
        ->name('farmers.dashboard');

    // Dashboard Pembeli
    Route::get('/buyers/dashboard', [DashboardController::class, 'pembeli'])
        ->middleware('role:pembeli')
        ->name('buyers.dashboard');

    // Produk CRUD (kecuali index & show)
    Route::resource('products', ProductController::class);

    // Pesanan
    Route::post('products/{product}/order', [OrderController::class, 'store'])->name('products.order');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    // Review produk
    Route::post('products/{product}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');
});
