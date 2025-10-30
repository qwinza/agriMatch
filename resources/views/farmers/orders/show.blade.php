@extends('layouts.app')

@section('title', 'Detail Pesanan - AgriMatch')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Pesanan</h1>
                        <p class="text-gray-600">Kode: {{ $order->order_code }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                            @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                        <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Product Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Produk</h2>
                        <div class="flex items-start space-x-4">
                            @if($order->product->images->count() > 0)
                                <img class="h-16 w-16 rounded-lg object-cover"
                                    src="{{ asset('storage/' . $order->product->images->first()->image_path) }}"
                                    alt="{{ $order->product->name }}">
                            @else
                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-seedling text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $order->product->name }}</h3>
                                <p class="text-gray-600">Petani: {{ $order->product->user->name }}</p>
                                <p class="text-gray-500 text-sm mt-1">{{ $order->product->description }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900">Rp
                                    {{ number_format($order->product->price, 0, ',', '.') }}/{{ $order->product->unit }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Pesanan</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Jumlah</p>
                                <p class="font-medium text-gray-900">{{ $order->quantity }} {{ $order->product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Harga Satuan</p>
                                <p class="font-medium text-gray-900">Rp
                                    {{ number_format($order->product->price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Harga</p>
                                <p class="text-lg font-semibold text-green-600">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Pesan</p>
                                <p class="font-medium text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pengiriman</h2>
                        <div>
                            <p class="text-sm text-gray-500">Alamat Pengiriman</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $order->shipping_address }}</p>
                        </div>
                        @if($order->notes)
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Catatan</p>
                                <p class="font-medium text-gray-900 mt-1">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions & Status -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            @if(auth()->user()->role === 'petani')
                                Informasi Pembeli
                            @else
                                Informasi Penjual
                            @endif
                        </h2>
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    @if(auth()->user()->role === 'petani')
                                        {{ $order->user->name }}
                                    @else
                                        {{ $order->product->user->name }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">
                                    @if(auth()->user()->role === 'petani')
                                        Pembeli
                                    @else
                                        Petani
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Aksi</h2>

                        @if(auth()->user()->role === 'petani')
                            <!-- Farmer Actions -->
                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="space-y-3">
                                    <select name="status"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>
                                            Dikonfirmasi</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                            Diproses</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim
                                        </option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                    <button type="submit"
                                        class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition">
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- Buyer Actions -->
                            @if($order->status === 'pending')
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition"
                                        onclick="return confirm('Yakin ingin membatalkan pesanan?')">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            @endif
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('orders.index') }}"
                                class="block text-center text-gray-600 hover:text-gray-800 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition">
                                Kembali ke Daftar Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection