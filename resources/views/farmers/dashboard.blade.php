@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 pt-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 pt-16">
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-tie text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-3">Halo, {{ $user->name }}! 👨‍🌾</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Selamat datang di <span class="font-semibold text-green-600">Dashboard Petani</span>. 
                Kelola produk Anda dengan mudah dan pantau pesanan dari pembeli secara real-time.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Produk -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Produk</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-blue-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    @if($newProductsThisWeek > 0)
                        <span class="text-xs font-medium text-green-500">+{{ $newProductsThisWeek }} baru minggu ini</span>
                    @else
                        <span class="text-xs font-medium text-gray-500">Tidak ada produk baru</span>
                    @endif
                </div>
            </div>

            <!-- Pesanan Menunggu -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pesanan Menunggu</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $pendingOrders }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-orange-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    @if($pendingOrders > 0)
                        <span class="text-xs font-medium text-orange-500">Perlu konfirmasi</span>
                    @else
                        <span class="text-xs font-medium text-gray-500">Tidak ada pesanan menunggu</span>
                    @endif
                </div>
            </div>

            <!-- Total Pendapatan Bulan Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pendapatan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-wallet text-green-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    @if($revenueIncrease > 0)
                        <span class="text-xs font-medium text-green-500">+{{ number_format($revenueIncrease, 1) }}% dari bulan lalu</span>
                    @elseif($revenueIncrease < 0)
                        <span class="text-xs font-medium text-red-500">{{ number_format($revenueIncrease, 1) }}% dari bulan lalu</span>
                    @else
                        <span class="text-xs font-medium text-gray-500">Stabil dari bulan lalu</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Tambahan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Total Penjualan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Penjualan</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalSales }}</p>
                        <p class="text-xs text-gray-500 mt-1">Pesanan berhasil diselesaikan</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-purple-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Nilai Pesanan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rata-rata Pesanan</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            Rp {{ number_format($totalSales > 0 ? $currentMonthRevenue / $totalSales : 0, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Per pesanan bulan ini</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-receipt text-indigo-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Tambah Produk -->
            <div class="group cursor-pointer">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-green-200 h-full">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-plus text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Tambah Produk</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Tambahkan produk hasil pertanian terbaru Anda ke dalam katalog
                        </p>
                        <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium py-3 px-6 rounded-xl hover:shadow-lg transition-all duration-300 group-hover:from-green-600 group-hover:to-emerald-700">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kelola Produk -->
            <div class="group cursor-pointer">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-blue-200 h-full">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-box-open text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Kelola Produk</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Kelola dan edit data produk yang sudah Anda tambahkan sebelumnya
                        </p>
                        <a href="{{ route('products.my-products') }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-3 px-6 rounded-xl hover:shadow-lg transition-all duration-300 group-hover:from-blue-600 group-hover:to-blue-700">
                            <i class="fas fa-edit mr-2"></i>
                            Kelola Produk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pesanan Masuk -->
            <div class="group cursor-pointer">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-orange-200 h-full">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-orange-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shopping-bag text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pesanan Masuk</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Lihat dan kelola pesanan dari pembeli dengan antarmuka yang intuitif
                        </p>
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium py-3 px-6 rounded-xl hover:shadow-lg transition-all duration-300 group-hover:from-orange-600 group-hover:to-orange-700">
                            <i class="fas fa-list mr-2"></i>
                            Lihat Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('reports.index') }}" class="flex flex-col items-center p-4 rounded-xl border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                    <i class="fas fa-chart-line text-green-500 text-xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Analitik</span>
                </a>
                <a href="{{ route('orders.index') }}?status=pending" class="flex flex-col items-center p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                    <i class="fas fa-users text-blue-500 text-xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Pelanggan</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex flex-col items-center p-4 rounded-xl border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-200">
                    <i class="fas fa-file-invoice-dollar text-purple-500 text-xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Laporan</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 rounded-xl border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-all duration-200">
                    <i class="fas fa-cog text-red-500 text-xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Pengaturan</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center z-50 hidden">
    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-green-600"></div>
</div>

<script>
// Optional: Add loading state for better UX
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href]');
    const spinner = document.getElementById('loadingSpinner');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href') !== '#' && !this.getAttribute('href').includes('javascript')) {
                spinner.classList.remove('hidden');
            }
        });
    });
    
    // Hide spinner when page is fully loaded
    window.addEventListener('load', function() {
        spinner.classList.add('hidden');
    });
});
</script>
@endsection