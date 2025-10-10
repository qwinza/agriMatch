@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h1 class="fw-bold mb-3">🌾 Selamat Datang di <span class="text-success">AgriMatch</span></h1>
    <p class="lead mb-4">
        Platform penghubung antara <strong>Petani</strong> dan <strong>Pembeli</strong> untuk hasil pertanian yang berkelanjutan.
    </p>

    @guest
        <a href="{{ route('login') }}" class="btn btn-success me-2">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-outline-success">Daftar</a>
    @endguest

    <hr class="my-5">

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm p-4">
                <h4>👨‍🌾 Untuk Petani</h4>
                <p>Jual hasil panenmu langsung ke pembeli, tanpa perantara.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4">
                <h4>🛒 Untuk Pembeli</h4>
                <p>Beli produk segar langsung dari petani terpercaya.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4">
                <h4>🌍 Misi Kami</h4>
                <p>Membangun rantai pasok pertanian yang adil dan berkelanjutan.</p>
            </div>
        </div>
    </div>
</div>
@endsection
