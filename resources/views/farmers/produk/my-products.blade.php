@extends('layouts.app')

@section('title', 'Produk Saya - AgriMatch')

@section('content')
<div class="min-h-screen bg-gray-50 pt-16">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Produk Saya</h1>
                    <p class="text-gray-600 mt-2">Kelola produk pertanian Anda</p>
                </div>
                <a href="{{ route('products.create') }}" 
                   class="bg-green-500 text-white px-6 py-3 rounded-xl hover:bg-green-600 transition font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Produk
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Foto Produk -->
                    <div class="h-48 bg-gray-200 overflow-hidden">
                        <img src="{{ $product->foto ? Storage::url($product->foto) : asset('images/default-product.jpg') }}" 
                             alt="{{ $product->nama_produk }}" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Info Produk -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->nama_produk }}</h3>
                        <p class="text-green-600 font-bold text-lg mb-2">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-box mr-2"></i>
                                <span>Stok: {{ $product->stok }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-2"></i>
                                <span>Kategori: {{ ucfirst($product->kategori) }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $product->lokasi }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('products.edit', encrypt($product->id)) }}" 
                               class="flex-1 bg-blue-500 text-white text-center py-2 rounded-lg hover:bg-blue-600 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form action="{{ route('products.destroy', encrypt($product->id)) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-seedling text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada produk</h3>
                <p class="text-gray-500 mb-6">Mulai tambahkan produk pertama Anda</p>
                <a href="{{ route('products.create') }}" 
                   class="bg-green-500 text-white px-6 py-3 rounded-xl hover:bg-green-600 transition font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Produk Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection