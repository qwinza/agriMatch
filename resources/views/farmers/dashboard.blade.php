@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>👨‍🌾 Halo, {{ $user->name }}</h1>
    <p>Selamat datang di Dashboard Petani!</p>

    <div class="card mt-3 p-3">
        <h4>Menu Petani</h4>
        <ul>
            <li><a href="{{ route('products.create') }}">Tambah Produk</a></li>
            <li><a href="{{ route('products.index') }}">Lihat Produk Saya</a></li>
            <li><a href="{{ route('orders.index') }}">Pesanan Masuk</a></li>
        </ul>
    </div>
</div>
@endsection
