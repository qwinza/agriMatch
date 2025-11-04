@extends('layouts.app')

@section('title', auth()->user()->role === 'petani' ? 'Pesanan Masuk - AgriMatch' : 'Pesanan Saya - AgriMatch')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 mt-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                        @if(auth()->user()->role === 'petani')
                            📦 Pesanan Masuk
                        @else
                            🛒 Pesanan Saya
                        @endif
                    </h1>
                    <p class="text-gray-600 text-sm sm:text-base">
                        @if(auth()->user()->role === 'petani')
                            Kelola pesanan dari pembeli dengan mudah
                        @else
                            Lacak dan kelola semua pesanan Anda
                        @endif
                    </p>
                </div>

                <!-- Status Cards -->
                @php
                    $statusCounts = [
                        'pending' => $orders->where('status', 'pending')->count(),
                        'confirmed' => $orders->where('status', 'confirmed')->count(),
                        'processing' => $orders->where('status', 'processing')->count(),
                        'completed' => $orders->where('status', 'completed')->count(),
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white rounded-lg p-3 shadow-sm border">
                        <div class="text-2xl font-bold text-yellow-600">{{ $statusCounts['pending'] }}</div>
                        <div class="text-xs text-gray-500">Pending</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm border">
                        <div class="text-2xl font-bold text-blue-600">{{ $statusCounts['confirmed'] }}</div>
                        <div class="text-xs text-gray-500">Dikonfirmasi</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm border">
                        <div class="text-2xl font-bold text-purple-600">{{ $statusCounts['processing'] }}</div>
                        <div class="text-xs text-gray-500">Diproses</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm border">
                        <div class="text-2xl font-bold text-green-600">{{ $statusCounts['completed'] }}</div>
                        <div class="text-xs text-gray-500">Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($orders->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/80 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Pesanan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Jumlah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition-all duration-200 group">
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                                <div class="font-semibold text-gray-900 text-sm">{{ $order->order_code }}</div>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($order->product->images->count() > 0)
                                                    <img class="h-12 w-12 rounded-xl object-cover shadow-sm border" src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" alt="{{ $order->product->name }}">
                                                @else
                                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-green-100 to-green-50 border border-green-200 flex items-center justify-center">
                                                        <i class="fas fa-seedling text-green-500 text-lg"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-gray-900 text-sm truncate">{{ $order->product->name }}</div>
                                                <div class="text-xs text-gray-500 flex items-center space-x-1">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                    <span>
                                                        @if(auth()->user()->role === 'pembeli')
                                                            {{ $order->product->user->name }}
                                                        @else
                                                            {{ $order->user->name }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm text-gray-900 font-medium">{{ $order->quantity }} {{ $order->product->unit }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @php
                                            $statusColors = [
                                                'pending' => ['bg'=>'bg-yellow-100','text'=>'text-yellow-800','dot'=>'bg-yellow-500'],
                                                'confirmed' => ['bg'=>'bg-blue-100','text'=>'text-blue-800','dot'=>'bg-blue-500'],
                                                'processing' => ['bg'=>'bg-purple-100','text'=>'text-purple-800','dot'=>'bg-purple-500'],
                                                'shipped' => ['bg'=>'bg-indigo-100','text'=>'text-indigo-800','dot'=>'bg-indigo-500'],
                                                'completed' => ['bg'=>'bg-green-100','text'=>'text-green-800','dot'=>'bg-green-500'],
                                                'cancelled' => ['bg'=>'bg-red-100','text'=>'text-red-800','dot'=>'bg-red-500'],
                                            ];
                                            $color = $statusColors[$order->status];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $color['bg'] }} {{ $color['text'] }} border border-opacity-50">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $color['dot'] }}"></span>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 text-xs font-medium rounded-lg border border-green-200 hover:bg-green-100 hover:border-green-300 transition-all duration-200">
                                                <i class="fas fa-eye mr-1.5"></i> Detail
                                            </a>

                                            @if(auth()->user()->role === 'pembeli' && $order->status === 'pending')
                                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-lg border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all duration-200" onclick="return confirm('Yakin ingin membatalkan pesanan?')">
                                                        <i class="fas fa-times mr-1.5"></i> Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="lg:hidden space-y-4 p-4">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="font-semibold text-gray-900 text-sm">{{ $order->order_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color['bg'] }} {{ $color['text'] }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3 mb-3">
                                @if($order->product->images->count() > 0)
                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" alt="{{ $order->product->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-seedling text-green-500"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-900 text-sm truncate">{{ $order->product->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if(auth()->user()->role === 'pembeli')
                                            {{ $order->product->user->name }}
                                        @else
                                            {{ $order->user->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                <div>
                                    <div class="text-gray-500 text-xs">Jumlah</div>
                                    <div class="font-medium text-gray-900">{{ $order->quantity }} {{ $order->product->unit }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500 text-xs">Total</div>
                                    <div class="font-semibold text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('orders.show', $order->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                                    <i class="fas fa-eye mr-2"></i> Detail
                                </a>
                                @if(auth()->user()->role === 'pembeli' && $order->status === 'pending')
                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg border border-red-200 hover:bg-red-100 transition-colors" onclick="return confirm('Yakin ingin membatalkan pesanan?')">
                                            <i class="fas fa-times mr-2"></i> Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <!-- Empty State -->
                <div class="text-center py-16 px-4">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada pesanan</h3>
                        <p class="text-gray-500 mb-6">
                            @if(auth()->user()->role === 'petani')
                                Belum ada pesanan yang masuk untuk produk Anda.
                            @else
                                Anda belum membuat pesanan apapun.
                            @endif
                        </p>
                        @if(auth()->user()->role === 'pembeli')
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-search mr-2"></i> Jelajahi Produk
                            </a>
                        @else
                            <a href="{{ route('products.my-products') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-seedling mr-2"></i> Kelola Produk
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
