@extends('layouts.app')

@section('content')
    <!-- Ganti py-8 dengan pt-16 untuk jarak dari navbar fixed -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 pt-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 pt-16">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-full flex items-center justify-center shadow-lg">
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
                <!-- Total Pesanan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOrders }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-blue-500 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-xs font-medium text-blue-500">{{ $pendingOrders }} menunggu</span>
                    </div>
                </div>

                <!-- Pesanan Aktif -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pesanan Aktif</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $activeOrders }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-truck text-green-500 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-xs font-medium text-green-500">{{ $processingOrders }} diproses</span>
                    </div>
                </div>

                <!-- Total Belanja -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Belanja</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">Rp
                                {{ number_format($totalSpending, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-purple-500 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-xs font-medium text-purple-500">Semua waktu</span>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Cari Produk -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-blue-200 h-full">
                        <div class="text-center">
                            <div
                                class="w-14 h-14 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-search text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Cari Produk</h3>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                Jelajahi berbagai produk pertanian segar
                            </p>
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-2 px-4 rounded-xl hover:shadow-lg transition-all duration-300 text-sm">
                                <i class="fas fa-search mr-2"></i>
                                Jelajahi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pesanan Saya -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-green-200 h-full">
                        <div class="text-center">
                            <div
                                class="w-14 h-14 bg-gradient-to-r from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-receipt text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Pesanan Saya</h3>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                Lacak dan kelola pesanan Anda
                            </p>
                            <a href="{{ route('orders.index') }}"
                                class="inline-flex items-center justify-center w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-medium py-2 px-4 rounded-xl hover:shadow-lg transition-all duration-300 text-sm">
                                <i class="fas fa-list mr-2"></i>
                                Lihat Pesanan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Marketplace -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:border-orange-200 h-full">
                        <div class="text-center">
                            <div
                                class="w-14 h-14 bg-gradient-to-r from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-store text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Marketplace</h3>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                Belanja produk terbaru
                            </p>
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center justify-center w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium py-2 px-4 rounded-xl hover:shadow-lg transition-all duration-300 text-sm">
                                <i class="fas fa-store mr-2"></i>
                                Belanja Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Pesanan Terbaru</h3>
                    <a href="{{ route('orders.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>

                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border">
                                        @if($order->product->foto)
                                            <img src="{{ asset('storage/' . $order->product->foto) }}"
                                                alt="{{ $order->product->nama_produk }}" class="w-8 h-8 rounded object-cover">
                                        @else
                                            <i class="fas fa-box text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $order->product->nama_produk }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $order->quantity }} item •
                                            {{ $order->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    <span class="inline-block px-2 py-1 text-xs rounded-full font-medium 
                                                @if($order->status == 'completed') bg-green-100 text-green-600
                                                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-600
                                                @elseif($order->status == 'processing') bg-purple-100 text-purple-600
                                                @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-600
                                                @elseif($order->status == 'cancelled') bg-red-100 text-red-600
                                                @else bg-yellow-100 text-yellow-600 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shopping-cart text-gray-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-600 mb-2">Belum ada pesanan</h4>
                        <p class="text-gray-500 text-sm mb-4">Mulai belanja dan buat pesanan pertama Anda</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            <i class="fas fa-store mr-2"></i>
                            Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order Status Summary -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Status Pesanan</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Menunggu Pembayaran</span>
                            <span class="font-semibold text-yellow-600">{{ $pendingOrders }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Diproses</span>
                            <span class="font-semibold text-blue-600">{{ $processingOrders }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Dikirim</span>
                            <span class="font-semibold text-indigo-600">{{ $shippedOrders }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Selesai</span>
                            <span class="font-semibold text-green-600">{{ $completedOrders }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Dibatalkan</span>
                            <span class="font-semibold text-red-600">{{ $cancelledOrders }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tips Pembeli -->
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-lg font-bold mb-2">💡 Tips Pembeli</h4>
                            <p class="text-blue-100 text-sm leading-relaxed">
                                • Periksa ketersediaan stok sebelum memesan<br>
                                • Baca ulasan dari pembeli sebelumnya<br>
                                • Perhatikan alamat pengiriman dengan benar<br>
                                • Hubungi penjual jika ada pertanyaan
                            </p>
                        </div>
                        <i class="fas fa-lightbulb text-2xl text-yellow-300 mt-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection