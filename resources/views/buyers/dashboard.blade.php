@extends('layouts.app')

@section('content')
<!-- Ganti py-8 dengan pt-16 untuk jarak dari navbar fixed -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 pt-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 pt-16">
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-shopping-cart text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-3">Halo, {{ $user->name }}! 🛒</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Selamat datang di <span class="font-semibold text-blue-600">Dashboard Pembeli</span>. 
                Temukan produk pertanian segar langsung dari petani terpercaya.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Active Orders -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pesanan Aktif</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">3</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-truck text-blue-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs font-medium text-blue-500">2 dalam pengiriman</span>
                </div>
            </div>

            <!-- Wishlist Items -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Favorit</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">8</p>
                    </div>
                    <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-heart text-pink-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs font-medium text-pink-500">Produk disukai</span>
                </div>
            </div>

            <!-- Total Spending -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Belanja</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">Rp 1.2Jt</p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-wallet text-green-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs font-medium text-green-500">Bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Cari Produk -->
            <div class="group cursor-pointer">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-blue-200 h-full">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-search text-white text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Cari Produk</h3>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            Jelajahi berbagai produk pertanian segar
                        </p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-2 px-4 rounded-xl hover:shadow-lg transition-all duration-300 text-sm">
                            <i class="fas fa-search mr-2"></i>
                            Jelajahi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pesanan Saya -->
            <div class="group cursor-pointer">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-green-200 h-full">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-receipt text-white text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Pesanan Saya</h3>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            Lacak dan kelola pesanan Anda
                        </p>
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-medium py-2 px-4 rounded-xl hover:shadow-lg transition-all duration-300 text-sm">
                            <i class="fas fa-list mr-2"></i>
                            Lihat Pesanan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Favorit -->
            <div class="group cursor-pointer">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-pink-200 h-full">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-pink-400 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-heart text-white text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Favorit</h3>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            Produk yang Anda simpan
                        </p>
                        <a href="#" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-pink-500 to-pink-600 text-white font-medium py-2 px-4 rounded-xl hover:shadow-lg transition-all duration-300 text-sm">
                            <i class="fas fa-heart mr-2"></i>
                            Lihat Favorit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profil -->
            <div class="group cursor-pointer">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-purple-200 h-full">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Profil Saya</h3>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            Kelola informasi akun Anda
                        </p>
                        <a href="#" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white font-medium py-2 px-4 rounded-xl hover:shadow-lg transition-all duration-300 text-sm">
                            <i class="fas fa-cog mr-2"></i>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Aktivitas Terbaru</h3>
                <a href="{{ route('orders.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
            
            <div class="space-y-4">
                <!-- Order Item 1 -->
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-truck text-blue-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Wortel Organik</p>
                            <p class="text-sm text-gray-500">Dalam pengiriman • 2 kg</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 45.000</p>
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded-full font-medium">Dikirim</span>
                    </div>
                </div>

                <!-- Order Item 2 -->
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Beras Merah</p>
                            <p class="text-sm text-gray-500">Selesai • 5 kg</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 120.000</p>
                        <span class="inline-block px-2 py-1 bg-green-100 text-green-600 text-xs rounded-full font-medium">Selesai</span>
                    </div>
                </div>

                <!-- Order Item 3 -->
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Tomat Cherry</p>
                            <p class="text-sm text-gray-500">Menunggu konfirmasi • 1 kg</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 35.000</p>
                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-600 text-xs rounded-full font-medium">Pending</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold mb-2">💡 Tips Pembeli</h4>
                    <p class="text-blue-100">Periksa ketersediaan stok sebelum memesan dan baca ulasan dari pembeli sebelumnya.</p>
                </div>
                <i class="fas fa-lightbulb text-2xl text-yellow-300"></i>
            </div>
        </div>
    </div>
</div>
@endsection