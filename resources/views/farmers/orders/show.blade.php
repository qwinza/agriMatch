@extends('layouts.app')

@section('title', 'Detail Pesanan - ' . $order->order_code)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 pt-16">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-3">Detail Pesanan 📦</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Informasi lengkap mengenai pesanan Anda
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <!-- Status Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">Status Pesanan</h2>
                            <div class="flex items-center gap-3">
                                @php
                                    $statusConfig = [
                                        'pending' => ['color' => 'yellow', 'icon' => '⏳'],
                                        'confirmed' => ['color' => 'green', 'icon' => '✅'],
                                        'processing' => ['color' => 'blue', 'icon' => '🔄'],
                                        'shipped' => ['color' => 'purple', 'icon' => '🚚'],
                                        'completed' => ['color' => 'green', 'icon' => '🎉'],
                                        'cancelled' => ['color' => 'red', 'icon' => '❌']
                                    ];
                                    $statusInfo = $statusConfig[$order->status] ?? ['color' => 'gray', 'icon' => '❓'];
                                    $colorClasses = [
                                        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'green' => 'bg-green-100 text-green-800 border-green-200',
                                        'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'red' => 'bg-red-100 text-red-800 border-red-200',
                                        'gray' => 'bg-gray-100 text-gray-800 border-gray-200'
                                    ];
                                @endphp
                                <span class="text-2xl">{{ $statusInfo['icon'] }}</span>
                                <span
                                    class="text-lg font-semibold capitalize {{ $colorClasses[$statusInfo['color']] }} px-3 py-1 rounded-full border">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                            <p class="text-sm text-gray-600">Kode Pesanan</p>
                            <p class="text-lg font-bold text-gray-800">{{ $order->order_code }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Produk -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">📦 Informasi Produk</h3>

                        <div class="flex items-start space-x-4">
                            <img src="{{ asset('storage/' . ($order->product->foto ?? 'images/default.jpg')) }}"
                                alt="{{ $order->product->nama_produk }}" class="w-20 h-20 object-cover rounded-xl">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 text-lg">{{ $order->product->nama_produk }}</h4>
                                <p class="text-gray-600 text-sm mt-1 line-clamp-2">
                                    {{ Str::limit($order->product->deskripsi, 100) }}
                                </p>
                                <div class="flex items-center justify-between mt-3">
                                    <div>
                                        <p class="text-sm text-gray-600">Harga Satuan</p>
                                        <p class="font-semibold text-green-600">Rp
                                            {{ number_format($order->product->harga, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Jumlah</p>
                                        <p class="font-semibold text-gray-800">{{ $order->quantity }} item</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 mt-4 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-800">Total Pembayaran</span>
                                <span class="text-2xl font-bold text-green-600">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">🚚 Informasi Pengiriman</h3>

                        <div class="space-y-4">
                            @php
                                // Cek apakah transaction tersedia dari controller
                                $transaction = $transaction ?? null;

                                // Jika tidak ada transaction, coba ambil dari order notes
                                if (!$transaction) {
                                    $recipientName = 'Data tidak tersedia';
                                    $phone = 'Data tidak tersedia';
                                    $transactionNotes = null;

                                    // Extract dari order notes jika ada format yang konsisten
                                    $orderNotes = $order->notes ?? '';
                                    if (str_contains($orderNotes, 'Penerima:')) {
                                        $parts = explode('|', $orderNotes);
                                        foreach ($parts as $part) {
                                            if (str_contains($part, 'Penerima:')) {
                                                $recipientName = trim(str_replace('Penerima:', '', $part));
                                            }
                                            if (str_contains($part, 'Telp:')) {
                                                $phone = trim(str_replace('Telp:', '', $part));
                                            }
                                        }
                                    }
                                } else {
                                    // Data dari transaction
                                    $recipientName = $transaction->recipient_name ?? 'Data tidak tersedia';
                                    $phone = $transaction->phone ?? 'Data tidak tersedia';
                                    $transactionNotes = $transaction->notes ?? null;
                                }
                            @endphp

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                                <p class="text-gray-900 font-semibold">{{ $recipientName }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                <p class="text-gray-900 font-semibold">{{ $phone }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                                <p class="text-gray-900 whitespace-pre-line">{{ $order->shipping_address }}</p>
                            </div>

                            @if($order->notes && !str_contains($order->notes, 'Penerima:'))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Order</label>
                                    <p class="text-gray-600 italic">"{{ $order->notes }}"</p>
                                </div>
                            @endif

                            @if($transactionNotes)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pengiriman</label>
                                    <p class="text-gray-600 italic">"{{ $transactionNotes }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informasi Pembayaran -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">💳 Informasi Pembayaran</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                            @php
                                $paymentStatusConfig = [
                                    'pending' => ['color' => 'yellow', 'text' => 'Menunggu Pembayaran'],
                                    'settlement' => ['color' => 'green', 'text' => 'Berhasil'],
                                    'success' => ['color' => 'green', 'text' => 'Berhasil'],
                                    'challenge' => ['color' => 'orange', 'text' => 'Butuh Verifikasi'],
                                    'deny' => ['color' => 'red', 'text' => 'Ditolak'],
                                    'expire' => ['color' => 'red', 'text' => 'Kadaluarsa'],
                                    'cancel' => ['color' => 'red', 'text' => 'Dibatalkan'],
                                    'cancelled' => ['color' => 'red', 'text' => 'Dibatalkan']
                                ];
                                $paymentInfo = $paymentStatusConfig[$order->payment_status] ?? ['color' => 'gray', 'text' => $order->payment_status];
                                $paymentColorClasses = [
                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                    'green' => 'bg-green-100 text-green-800',
                                    'orange' => 'bg-orange-100 text-orange-800',
                                    'red' => 'bg-red-100 text-red-800',
                                    'gray' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span
                                class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $paymentColorClasses[$paymentInfo['color']] }}">
                                {{ $paymentInfo['text'] }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <p class="text-gray-900 font-semibold capitalize">{{ $order->payment_method ?? 'Midtrans' }}</p>
                        </div>

                        @if($order->midtrans_transaction_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ID Transaksi</label>
                                <p class="text-gray-900 font-mono text-sm">{{ $order->midtrans_transaction_id }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Pembayaran</label>
                            <p class="text-gray-900">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">🕒 Timeline Pesanan</h3>

                    <div class="space-y-4">
                        @php
                            $timelineSteps = [
                                'pending' => ['icon' => '⏳', 'title' => 'Pesanan Dibuat', 'description' => 'Pesanan telah dibuat dan menunggu pembayaran'],
                                'confirmed' => ['icon' => '✅', 'title' => 'Pembayaran Dikonfirmasi', 'description' => 'Pembayaran berhasil dan pesanan dikonfirmasi'],
                                'processing' => ['icon' => '🔄', 'title' => 'Sedang Diproses', 'description' => 'Pesanan sedang dipersiapkan oleh penjual'],
                                'shipped' => ['icon' => '🚚', 'title' => 'Dikirim', 'description' => 'Pesanan sedang dalam pengiriman'],
                                'completed' => ['icon' => '🎉', 'title' => 'Selesai', 'description' => 'Pesanan telah sampai dan selesai']
                            ];

                            $currentStep = array_keys($timelineSteps);
                            $currentIndex = array_search($order->status, $currentStep);
                            if ($currentIndex === false)
                                $currentIndex = -1;
                        @endphp

                        @foreach($timelineSteps as $step => $info)
                            @php
                                $stepIndex = array_search($step, $currentStep);
                                $isActive = $currentIndex >= $stepIndex;
                                $isCurrent = $order->status === $step;
                            @endphp
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                                    {{ $isActive ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}
                                                    {{ $isCurrent ? 'ring-2 ring-green-300 ring-offset-2' : '' }}">
                                        <span class="text-lg">{{ $info['icon'] }}</span>
                                    </div>
                                    @if(!$loop->last)
                                        <div
                                            class="h-8 w-0.5 {{ $isActive && $currentIndex > $stepIndex ? 'bg-green-500' : 'bg-gray-200' }} mx-auto mt-1">
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-6">
                                    <h4 class="font-semibold {{ $isActive ? 'text-green-600' : 'text-gray-500' }}">
                                        {{ $info['title'] }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $info['description'] }}</p>
                                    @if($isCurrent && $step !== 'completed')
                                        <p class="text-xs text-green-600 font-medium mt-2">Status saat ini</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                    <a href="{{ route('transactions.my-orders') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 text-center">
                        ← Kembali ke Daftar Pesanan
                    </a>

                    @if($order->status === 'completed')
                        <a href="#"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 text-center">
                            📞 Hubungi Penjual
                        </a>
                    @endif
                </div>

                <!-- Debug Info (Hanya untuk development) -->
                @if(env('APP_DEBUG'))
                    <div class="mt-8 p-4 bg-gray-100 rounded-lg">
                        <h3 class="font-bold mb-2">Debug Information:</h3>
                        <p><strong>Order ID:</strong> {{ $order->id }}</p>
                        <p><strong>User ID:</strong> {{ $order->user_id }}</p>
                        <p><strong>Current User ID:</strong> {{ Auth::id() }}</p>
                        <p><strong>Is Owner:</strong> {{ $order->user_id == Auth::id() ? 'Yes' : 'No' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection