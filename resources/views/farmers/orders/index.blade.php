@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
        <div class="container mx-auto px-4">
            <!-- Header Section -->
            <div class="text-center mb-12 pt-8">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-shopping-bag text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-3">Pesanan Saya 🛍️</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Kelola dan pantau semua pesanan Anda di satu tempat.
                    <span class="font-semibold text-green-600">Lacak status pesanan</span> dan pembayaran dengan mudah.
                </p>

                <!-- Auto Success Toggle -->
                @if(app()->environment('local') || app()->environment('development'))
                    <div class="mt-6 inline-flex items-center bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bolt text-yellow-600 text-sm"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-medium text-yellow-800">Mode Testing</p>
                                <p class="text-xs text-yellow-600">Auto Success Payment tersedia</p>
                            </div>
                            <button id="togglePaymentMode"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-sync-alt mr-1"></i>
                                <span id="modeText">Auto: OFF</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-green-800 font-semibold">Berhasil!</h3>
                            <p class="text-green-600 mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($orders->count() > 0)
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Total Pesanan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">
                                    {{ $orders->total() }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Menunggu Konfirmasi -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Menunggu Konfirmasi</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">
                                    {{ $orders->where('status', 'pending')->count() }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock text-orange-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Sedang Diproses -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Sedang Diproses</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">
                                    {{ $orders->where('status', 'confirmed')->count() }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-truck text-purple-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Selesai -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Selesai</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">
                                    {{ $orders->where('status', 'completed')->count() }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Daftar Pesanan</h2>
                        <p class="text-gray-600 text-sm mt-1">Semua pesanan yang pernah Anda buat</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Kuantitas
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($orders as $order)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                        <td class="px-6 py-4">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-12 w-12">
                                                                    @if($order->product->foto)
                                                                        <img class="h-12 w-12 rounded-lg object-cover border border-gray-200"
                                                                            src="{{ asset('storage/' . $order->product->foto) }}"
                                                                            alt="{{ $order->product->nama_produk }}">
                                                                    @else
                                                                        <img class="h-12 w-12 rounded-lg object-cover border border-gray-200"
                                                                            src="{{ asset('images/default-product.jpg') }}" alt="Default">
                                                                    @endif
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="text-sm font-semibold text-gray-900">
                                                                        {{ $order->product->nama_produk }}
                                                                    </div>
                                                                    <div class="text-xs text-gray-500">
                                                                        Kode: {{ $order->order_code }}
                                                                    </div>
                                                                    <div class="text-xs text-gray-400 mt-1">
                                                                        Oleh: {{ $order->product->user->name }}
                                                                    </div>
                                                                    @if($order->payment_method === 'auto_success')
                                                                        <div class="text-xs text-yellow-600 font-medium mt-1">
                                                                            <i class="fas fa-bolt mr-1"></i>Auto Success
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <div class="text-sm text-gray-900 font-semibold">{{ $order->quantity }}</div>
                                                            <div class="text-xs text-gray-500">pcs</div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <div class="text-sm font-bold text-gray-900">
                                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            @php
                                                                $statusColors = [
                                                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                                    'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                                    'processing' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                                    'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200'
                                                                ];
                                                                $statusColor = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                            @endphp
                                      <span
                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $statusColor }}">
                                                                {{ ucfirst($order->status) }}
                                                            </span>
                                                            @if($order->payment_status)
                                                                <div class="text-xs text-gray-500 mt-1">
                                                                    Payment: {{ ucfirst($order->payment_status) }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <div class="text-sm text-gray-900">
                                                                {{ $order->created_at->format('d M Y') }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ $order->created_at->format('H:i') }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <a href="{{ route('orders.show', $order->id) }}"
                                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:shadow-lg transition-all duration-300 hover:from-green-600 hover:to-emerald-700">
                                                                <i class="fas fa-eye mr-2"></i>
                                                                Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div
                            class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum ada pesanan</h3>
                        <p class="text-gray-600 mb-8 leading-relaxed">
                            Mulai jelajahi produk terbaik dari petani lokal dan buat pesanan pertama Anda!
                        </p>
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium py-4 px-8 rounded-xl hover:shadow-lg transition-all duration-300 hover:from-green-600 hover:to-emerald-700">
                            <i class="fas fa-store mr-3"></i>
                            Jelajahi Produk
                        </a>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            @if($orders->count() > 0)
                <div class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Butuh Bantuan?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="#"
                            class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-question-circle text-green-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Pusat Bantuan</div>
                                <div class="text-sm text-gray-600">Cari solusi untuk masalah Anda</div>
                            </div>
                        </a>
                        <a href="#"
                            class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-headset text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Hubungi Kami</div>
                                <div class="text-sm text-gray-600">Butuh bantuan langsung?</div>
                            </div>
                        </a>
                        <a href="{{ route('home') }}"
                            class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-200">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-store text-orange-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Lanjut Belanja</div>
                                <div class="text-sm text-gray-600">Temukan produk lainnya</div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div class="text-center">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-green-600 mx-auto mb-4"></div>
            <p class="text-gray-600 font-medium">Memuat...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const links = document.querySelectorAll('a[href]');
            const spinner = document.getElementById('loadingSpinner');

            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    if (this.getAttribute('href') !== '#' && !this.getAttribute('href').includes('javascript')) {
                        spinner.classList.remove('hidden');
                    }
                });
            });

            window.addEventListener('load', function () {
                spinner.classList.add('hidden');
            });

            // Auto Success Payment Mode Toggle
            const toggleButton = document.getElementById('togglePaymentMode');
            const modeText = document.getElementById('modeText');

            if (toggleButton) {
                let autoSuccessMode = localStorage.getItem('autoSuccessMode') === 'true';
                updateModeDisplay();

                toggleButton.addEventListener('click', function () {
                    autoSuccessMode = !autoSuccessMode;
                    localStorage.setItem('autoSuccessMode', autoSuccessMode);
                    updateModeDisplay();

                    // Show notification
                    showNotification(
                        autoSuccessMode ? 'Auto Success Mode: ON' : 'Auto Success Mode: OFF',
                        autoSuccessMode ? 'success' : 'info'
                    );
                });

                function updateModeDisplay() {
                    modeText.textContent = `Auto: ${autoSuccessMode ? 'ON' : 'OFF'}`;
                    toggleButton.className = autoSuccessMode
                        ? 'bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors'
                        : 'bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors';
                }

                function showNotification(message, type) {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg border transform transition-transform duration-300 ${type === 'success'
                            ? 'bg-green-50 border-green-200 text-green-800'
                            : 'bg-blue-50 border-blue-200 text-blue-800'
                        }`;
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} mr-3"></i>
                            <span class="font-medium">${message}</span>
                        </div>
                    `;
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            }
        });
    </script>

    <style>
        /* Custom pagination styling */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            justify-content: center;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #374151;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination li a:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination li.active span {
            background: linear-gradient(135deg, #10b981, #059669);
            border-color: #059669;
            color: white;
        }

        .pagination li.disabled span {
            background: #f9fafb;
            color: #9ca3af;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
    </style>
@endsection