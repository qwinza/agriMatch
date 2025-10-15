@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">🛒 Halo, {{ $user->name }}!</h2>
        <p class="text-muted">Selamat datang di <span class="fw-semibold">Dashboard Pembeli</span></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="card-title mb-3 text-primary fw-bold">Menu Pembeli</h4>
                    <div class="list-group">
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-bag"></i> Lihat Semua Produk</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-receipt"></i> Pesanan Saya</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-star"></i> Ulasan Produk</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="btn btn-outline-danger px-4 rounded-pill">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
