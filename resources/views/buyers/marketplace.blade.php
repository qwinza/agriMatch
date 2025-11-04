@extends('layouts.app')

@section('title', 'Marketplace - AgriMatch')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-store text-white text-xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Marketplace AgriMatch</h1>
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">
                    Temukan produk pertanian segar langsung dari petani terpercaya
                </p>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-8">
                <form action="{{ route('buyer.marketplace') }}" method="GET" class="space-y-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="Cari produk, kategori, petani, atau lokasi...">
                    </div>

                    <!-- Filter Row -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" id="kategori"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Location Filter -->
                        <div>
                            <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                            <select name="lokasi" id="lokasi"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="">Semua Lokasi</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location }}" {{ request('lokasi') == $location ? 'selected' : '' }}>
                                        {{ $location }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label for="harga_min" class="block text-sm font-medium text-gray-700 mb-1">Harga Min</label>
                            <input type="number" name="harga_min" id="harga_min" value="{{ request('harga_min') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                placeholder="Rp 0">
                        </div>

                        <div>
                            <label for="harga_max" class="block text-sm font-medium text-gray-700 mb-1">Harga Max</label>
                            <input type="number" name="harga_max" id="harga_max" value="{{ request('harga_max') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                placeholder="Rp 1000000">
                        </div>
                    </div>

                    <!-- Sort and Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <!-- Sort Options -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">Urutkan:</span>
                            <select name="sort"
                                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga
                                    Terendah</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga
                                    Tertinggi</option>
                                <option value="stock_high" {{ request('sort') == 'stock_high' ? 'selected' : '' }}>Stok
                                    Terbanyak</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            <button type="reset" onclick="window.location='{{ route('buyer.marketplace') }}'"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                <i class="fas fa-refresh mr-2"></i>Reset
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                                <i class="fas fa-filter mr-2"></i>Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Info -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $products->total() }}</span> produk
                    @if(request()->anyFilled(['search', 'kategori', 'lokasi', 'harga_min', 'harga_max']))
                        berdasarkan filter yang dipilih
                    @endif
                </p>

                <!-- Cart Info -->
                @php
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                <a href="{{ route('buyer.cart') }}"
                    class="flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Keranjang
                    @if($cartCount > 0)
                        <span
                            class="ml-2 bg-white text-green-600 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($products as $product)
                        <div
                            class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                            <!-- Product Image -->
                            <div class="relative h-48 bg-gray-200">
                                @if($product->foto)
                                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <i class="fas fa-seedling text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                                <!-- Category Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="bg-green-500 text-white text-xs font-medium px-2 py-1 rounded-full">
                                        {{ ucfirst($product->kategori) }}
                                    </span>
                                </div>
                                <!-- Stock Badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-full">
                                        Stok: {{ $product->stok }}
                                    </span>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-2">
                                    {{ $product->nama_produk }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                    {{ Str::limit($product->deskripsi, 80) }}
                                </p>

                                <!-- Farmer Info -->
                                <div class="flex items-center mb-3">
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-user text-green-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $product->user->name }}</span>
                                </div>

                                <!-- Location -->
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-map-marker-alt text-gray-400 text-xs mr-2"></i>
                                    <span class="text-xs text-gray-500">{{ $product->lokasi }}</span>
                                </div>

                                <!-- Price and Action -->
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-2xl font-bold text-green-600">
                                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-500">per item</p>
                                    </div>

                                    <!-- Add to Cart Form -->
                                    <form action="{{ route('buyer.cart.add') }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition font-medium text-sm add-to-cart-btn">
                                            <i class="fas fa-cart-plus mr-1"></i>Keranjang
                                        </button>
                                    </form>
                                </div>

                                <!-- Quick View Button -->
                                <div class="mt-3">
                                    <a href="{{ route('buyer.product.detail', Crypt::encrypt($product->id)) }}"
                                        class="block text-center w-full py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-sm">
                                        <i class="fas fa-eye mr-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
                    <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Produk tidak ditemukan</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request()->anyFilled(['search', 'kategori', 'lokasi', 'harga_min', 'harga_max']))
                            Coba ubah filter pencarian Anda atau
                        @endif
                        Coba kata kunci lain yang lebih umum
                    </p>
                    <a href="{{ route('buyer.marketplace') }}"
                        class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                        <i class="fas fa-refresh mr-2"></i>Tampilkan Semua Produk
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .transition {
            transition: all 0.3s ease;
        }
    </style>

    <!-- JavaScript for enhanced functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-submit form when certain filters change
            const autoSubmitFilters = ['kategori', 'lokasi', 'sort'];
            autoSubmitFilters.forEach(filter => {
                const element = document.getElementById(filter);
                if (element) {
                    element.addEventListener('change', function () {
                        this.form.submit();
                    });
                }
            });

            // Price range validation
            const hargaMin = document.getElementById('harga_min');
            const hargaMax = document.getElementById('harga_max');

            if (hargaMin && hargaMax) {
                hargaMin.addEventListener('change', function () {
                    if (hargaMax.value && parseInt(this.value) > parseInt(hargaMax.value)) {
                        this.value = hargaMax.value;
                    }
                });

                hargaMax.addEventListener('change', function () {
                    if (hargaMin.value && parseInt(this.value) < parseInt(hargaMin.value)) {
                        this.value = hargaMin.value;
                    }
                });
            }

            // Add to cart functionality
            const addToCartForms = document.querySelectorAll('.add-to-cart-form');
            addToCartForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const button = this.querySelector('.add-to-cart-btn');
                    const originalText = button.innerHTML;

                    // Show loading state
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menambah...';
                    button.disabled = true;

                    // Submit form via AJAX
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: this.querySelector('[name="product_id"]').value,
                            quantity: this.querySelector('[name="quantity"]').value
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');

                                // Update cart count
                                updateCartCount(data.cart_count);
                            } else {
                                showNotification(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            showNotification('Terjadi kesalahan saat menambah ke keranjang', 'error');
                        })
                        .finally(() => {
                            // Restore button state
                            button.innerHTML = originalText;
                            button.disabled = false;
                        });
                });
            });

            function updateCartCount(count) {
                const cartCountElement = document.querySelector('.bg-white.text-green-600');
                const cartLink = document.querySelector('a[href*="cart"]');

                if (cartCountElement) {
                    cartCountElement.textContent = count;
                } else if (count > 0 && cartLink) {
                    // Create cart count badge if it doesn't exist
                    const badge = document.createElement('span');
                    badge.className = 'ml-2 bg-white text-green-600 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold';
                    badge.textContent = count;
                    cartLink.appendChild(badge);
                }
            }

            function showNotification(message, type) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition transform duration-300 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                    }`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        });
    </script>
@endsection