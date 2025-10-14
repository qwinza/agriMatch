@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section id="beranda" class="relative bg-cover bg-center h-screen flex items-start"
        style="background-image: url('{{ asset('images/bg-landing.jpg') }}'); background-size: cover; background-position: center;">

        <!-- Gunakan padding yang sama dengan navbar -->
        <div class="container mx-auto px-4 md:px-6 lg:px-6 pt-80">
            <div class="max-w-2xl text-white">
                <h1 class="text-4xl md:text-5xl font-extrabold leading-snug mb-4 text-left">
                    Hubungkan <span class="text-green-400">Petani</span> &
                    <span class="text-green-400">Pembeli</span><br>
                    dengan Cara yang <span class="text-green-400">Mudah</span>
                </h1>

                <p class="text-lg text-gray-100 max-w-xl mb-8 text-left">
                    AgriMatch adalah platform digital yang mempertemukan petani dan pembeli secara langsung,
                    mendukung perdagangan hasil pertanian yang efisien, adil, dan berkelanjutan 🌱
                </p>

                <a href="{{ route('register') }}"
                    class="bg-green-500 text-white px-6 py-3 rounded-md font-semibold hover:bg-green-600 transition">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </section>


    <!-- Tentang -->
    <section id="tentang" class="py-20 px-8 text-center bg-[#f8f8f8]">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Tentang AgriMatch</h2>
        <p class="max-w-3xl mx-auto text-gray-600">
            Kami membantu petani memasarkan hasil panen mereka langsung kepada pembeli,
            tanpa perantara dan dengan transparansi harga.
            Dengan sistem berbasis data, kami memastikan rantai pasok pertanian berjalan lebih efisien
            sekaligus menguntungkan semua pihak.
        </p>
    </section>

    <!-- Fitur -->
    <section id="fitur" class="py-20 px-8 bg-[#f5f3ee] text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-10">Fitur Utama</h2>
        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="p-6 bg-white rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-2 text-green-700">📦 Marketplace Pertanian</h3>
                <p class="text-gray-600">Jual dan beli hasil pertanian dengan mudah langsung dari petani ke pembeli.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-2 text-green-700">📈 Analisis Data</h3>
                <p class="text-gray-600">Pantau harga pasar, tren permintaan, dan hasil panen melalui data real-time.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-2 text-green-700">🤝 Konektivitas Langsung</h3>
                <p class="text-gray-600">Bangun hubungan bisnis jangka panjang antara petani dan pembeli terpercaya.</p>
            </div>
        </div>
    </section>
@endsection