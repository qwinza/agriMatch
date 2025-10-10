@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>🛒 Halo, {{ $user->name }}</h1>
    <p>Selamat datang di Dashboard Pembeli!</p>

    <div class="card mt-3 p-3">
        <h4>Menu Pembeli</h4>
        <ul>
            <li><a href="{{ route('home') }}">Lihat Semua Produk</a></li>
            <li><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
            <li><a href="#">Ulasan Produk</a></li>
        </ul>
    </div>
</div>
@endsection
