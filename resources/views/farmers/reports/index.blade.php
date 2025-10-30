@extends('layouts.app')

@section('title', 'Laporan & Analitik - AgriMatch')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                            📊 Laporan & Analitik
                        </h1>
                        <p class="text-gray-600">
                            @if(auth()->user()->role === 'petani')
                                Analisis performa penjualan dan statistik produk
                            @else
                                Ringkasan aktivitas belanja dan pengeluaran
                            @endif
                        </p>
                    </div>

                    <!-- Filter Controls -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select id="filterSelect"
                            class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="daily" {{ $filter === 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ $filter === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ $filter === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ $filter === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>

                        <input type="{{ $filter === 'yearly' ? 'number' : ($filter === 'monthly' ? 'month' : 'date') }}"
                            id="dateSelect" value="{{ $date }}"
                            class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">

                        <button onclick="applyFilter()"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            Terapkan
                        </button>

                        <button onclick="exportReport()"
                            class="bg-white border border-green-600 text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors text-sm font-medium">
                            📥 Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @if(auth()->user()->role === 'petani')
                    <!-- Petani Stats -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_orders'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-gray-500">Periode terpilih</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">Rp
                                    {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-gray-500">Dari pesanan selesai</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pesanan Selesai</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['completed_orders'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span
                                class="text-xs text-gray-500">
                                @php
                                    $totalOrders = $stats['total_orders'] ?? 0;
                                    $completedOrders = $stats['completed_orders'] ?? 0;
                                    $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
                                @endphp
                                {{ $successRate }}% success rate
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Rata-rata Nilai</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">Rp
                                    {{ number_format($stats['average_order_value'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-gray-500">Per pesanan</span>
                        </div>
                    </div>

                @else
                    <!-- Pembeli Stats -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_orders'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-gray-500">Periode terpilih</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">Rp
                                    {{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-gray-500">Untuk pesanan selesai</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pesanan Selesai</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['completed_orders'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span
                                class="text-xs text-gray-500">
                                @php
                                    $totalOrders = $stats['total_orders'] ?? 0;
                                    $completedOrders = $stats['completed_orders'] ?? 0;
                                    $completionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
                                @endphp
                                {{ $completionRate }}% completion rate
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Rata-rata Belanja</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">Rp
                                    {{ number_format($stats['average_order_value'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-gray-500">Per pesanan</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Chart Section -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        @if(auth()->user()->role === 'petani')
                            📈 Grafik Penjualan
                        @else
                            📈 Grafik Pengeluaran
                        @endif
                    </h3>
                    <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-chart-bar text-4xl mb-2"></i>
                            <p>Data grafik akan ditampilkan di sini</p>
                            <p class="text-sm">(Integrasi chart library dapat ditambahkan)</p>
                        </div>
                    </div>
                </div>

                <!-- Top Products/Farmers -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        @if(auth()->user()->role === 'petani')
                            🏆 Produk Terlaris
                        @else
                            👨‍🌾 Petani Favorit
                        @endif
                    </h3>

                    @if(auth()->user()->role === 'petani')
                        <!-- PERBAIKAN: Tambahkan pengecekan null untuk $topProducts -->
                        @if(isset($topProducts) && $topProducts->count() > 0)
                            <div class="space-y-4">
                                @foreach($topProducts as $product)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <!-- PERBAIKAN: Gunakan field yang sesuai dengan model Produk -->
                                            @if($product->foto)
                                                <img src="{{ asset('storage/' . $product->foto) }}"
                                                    alt="{{ $product->nama_produk }}" class="w-10 h-10 rounded-lg object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                                    <i class="fas fa-seedling text-green-500"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <!-- PERBAIKAN: Gunakan nama_produk bukan name -->
                                                <p class="font-medium text-gray-900 text-sm">{{ $product->nama_produk }}</p>
                                                <p class="text-xs text-gray-500">{{ $product->total_orders ?? 0 }} pesanan</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-green-600 text-sm">Rp
                                                {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">pendapatan</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-chart-line text-3xl mb-2"></i>
                                <p>Belum ada data produk</p>
                            </div>
                        @endif
                    @else
                        <!-- PERBAIKAN: Tambahkan pengecekan null untuk $favoriteFarmers -->
                        @if(isset($favoriteFarmers) && $favoriteFarmers->count() > 0)
                            <div class="space-y-4">
                                @foreach($favoriteFarmers as $farmerData)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($farmerData['farmer']->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm">{{ $farmerData['farmer']->name ?? 'Unknown Farmer' }}</p>
                                                <p class="text-xs text-gray-500">{{ $farmerData['order_count'] ?? 0 }} pesanan</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-green-600 text-sm">Rp
                                                {{ number_format($farmerData['total_spent'] ?? 0, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">total belanja</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-users text-3xl mb-2"></i>
                                <p>Belum ada data petani</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- PERBAIKAN: Tambahkan pengecekan null untuk $orders -->
                            @if(isset($orders) && $orders->count() > 0)
                                @foreach($orders->take(5) as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $order->order_code }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <!-- PERBAIKAN: Gunakan nama_produk bukan name -->
                                            <div class="text-sm text-gray-900">{{ $order->product->nama_produk ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">
                                                @if(auth()->user()->role === 'pembeli')
                                                    {{ $order->product->user->name ?? 'Unknown' }}
                                                @else
                                                    {{ $order->user->name ?? 'Unknown' }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $order->quantity }}
                                            <!-- PERBAIKAN: Hapus unit jika tidak ada di model -->
                                            {{-- {{ $order->product->unit }} --}}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp
                                            {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                                                    @else bg-blue-100 text-blue-800
                                                    @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                                        <p>Belum ada pesanan</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function applyFilter() {
            const filter = document.getElementById('filterSelect').value;
            const date = document.getElementById('dateSelect').value;

            const url = new URL(window.location.href);
            url.searchParams.set('filter', filter);
            url.searchParams.set('date', date);

            window.location.href = url.toString();
        }

        function exportReport() {
            const filter = document.getElementById('filterSelect').value;
            const date = document.getElementById('dateSelect').value;

            const url = new URL('{{ route("reports.export") }}');
            url.searchParams.set('filter', filter);
            url.searchParams.set('date', date);
            url.searchParams.set('type', 'pdf');

            window.open(url.toString(), '_blank');
        }

        // Update date input type based on filter
        document.getElementById('filterSelect').addEventListener('change', function () {
            const filter = this.value;
            const dateInput = document.getElementById('dateSelect');

            switch (filter) {
                case 'daily':
                    dateInput.type = 'date';
                    break;
                case 'weekly':
                    dateInput.type = 'week';
                    break;
                case 'monthly':
                    dateInput.type = 'month';
                    break;
                case 'yearly':
                    dateInput.type = 'number';
                    dateInput.min = '2020';
                    dateInput.max = '2030';
                    break;
            }
        });
    </script>
@endsection