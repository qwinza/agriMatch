@extends('layouts.app')

@section('title', $product->nama_produk . ' - Detail Produk')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 pt-24 pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12 mt-20">
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-leaf text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $product->nama_produk }}</h1>
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">
                    Detail lengkap produk hasil pertanian pilihan dari petani terpercaya
                </p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                    <!-- Left: Gambar Produk -->
                    <div>
                        <div
                            class="bg-gray-100 rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition duration-300">
                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}"
                                class="w-full h-[400px] object-cover">
                        </div>
                        <p class="text-gray-500 text-xs mt-3 text-center">Foto produk sesuai tampilan aslinya</p>
                    </div>

                    <!-- Right: Informasi Produk -->
                    <div class="flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="inline-flex items-center bg-green-100 text-green-700 text-sm font-medium px-4 py-1.5 rounded-full">
                                    <i class="fas fa-seedling mr-2"></i> {{ ucfirst($product->kategori) }}
                                </span>
                                <span class="text-gray-500 text-sm">
                                    <i class="fas fa-map-marker-alt mr-1 text-green-500"></i>{{ $product->lokasi }}
                                </span>
                            </div>

                            <h2 class="text-3xl font-bold text-gray-900 mb-3">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </h2>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                {{ $product->deskripsi }}
                            </p>

                            <div class="space-y-2 text-sm text-gray-600">
                                <p><i class="fas fa-cubes text-green-500 mr-2"></i>Stok tersedia:
                                    <strong>{{ $product->stok }}</strong>
                                </p>
                                <p><i class="fas fa-user text-green-500 mr-2"></i>Dijual oleh:
                                    <strong>{{ $product->user->name ?? 'Petani' }}</strong>
                                </p>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <!-- Tombol Aksi -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('transactions.create', $product->encryptedId) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-xl text-white font-semibold 
                                            bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 shadow-md hover:shadow-lg 
                                            transition duration-200 transform hover:-translate-y-0.5">
                                Beli Sekarang
                            </a>

                            <a href="{{ url()->previous() }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-xl border border-gray-300 
                                            text-gray-700 bg-white hover:bg-gray-50 transition duration-200 font-medium shadow-sm hover:shadow-md">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Informasi Tambahan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">
                <!-- Tips -->
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-blue-500 text-lg mt-1"></i>
                        <div class="ml-3">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Pembelian Aman</h4>
                            <ul class="text-xs text-blue-700 space-y-1.5">
                                <li><i class="fas fa-check text-blue-500 mr-2 text-xs"></i>Periksa deskripsi produk
                                    dengan teliti</li>
                                <li><i class="fas fa-check text-blue-500 mr-2 text-xs"></i>Hubungi penjual jika ada
                                    pertanyaan</li>
                                <li><i class="fas fa-check text-blue-500 mr-2 text-xs"></i>Gunakan sistem transaksi
                                    resmi AgriMatch</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Keunggulan -->
                <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                    <div class="flex items-start">
                        <i class="fas fa-leaf text-green-500 text-lg mt-1"></i>
                        <div class="ml-3">
                            <h4 class="text-sm font-semibold text-green-800 mb-2">Keunggulan Produk Lokal</h4>
                            <ul class="text-xs text-green-700 space-y-1.5">
                                <li><i class="fas fa-check text-green-500 mr-2 text-xs"></i>Langsung dari petani tanpa
                                    perantara</li>
                                <li><i class="fas fa-check text-green-500 mr-2 text-xs"></i>Produk segar dan alami</li>
                                <li><i class="fas fa-check text-green-500 mr-2 text-xs"></i>Mendukung ekonomi lokal</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Animasi lembut */
        .transition {
            transition: all 0.25s ease-in-out;
        }

        img {
            transition: transform 0.3s ease-in-out;
        }

        img:hover {
            transform: scale(1.02);
        }
    </style>
@endsection