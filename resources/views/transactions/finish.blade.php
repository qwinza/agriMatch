@extends('layouts.app')

@section('title', 'Selesai Pembayaran')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center pt-16">
        <div class="container mx-auto px-4">
            <div class="max-w-lg mx-auto">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    @if($isSuccess)
                        <!-- Success State -->
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil! 🎉</h2>
                            <p class="text-gray-600 mb-4">Terima kasih, pesanan Anda telah tercatat dan akan segera diproses.
                            </p>

                            @if($order)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                                    <p class="text-sm text-green-800">
                                        <strong>Kode Pesanan:</strong> {{ $order->order_code }}<br>
                                        <strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>

                    @elseif($displayStatus == 'pending')
                        <!-- Pending State -->
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clock text-yellow-500 text-4xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Menunggu Pembayaran</h2>
                            <p class="text-gray-600 mb-4">
                                Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran sesuai metode yang dipilih.
                            </p>

                            @if($order)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                                    <p class="text-sm text-yellow-800">
                                        <strong>Kode Pesanan:</strong> {{ $order->order_code }}<br>
                                        <strong>Status:</strong> {{ ucfirst($status) }}
                                    </p>
                                </div>
                            @endif
                        </div>

                    @else
                        <!-- Failed/Unknown State -->
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                @if($displayStatus == 'failure')
                                    Pembayaran Gagal
                                @else
                                    Status Tidak Dikenal
                                @endif
                            </h2>
                            <p class="text-gray-600 mb-4">
                                @if($displayStatus == 'failure')
                                    Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau gunakan metode pembayaran
                                    lain.
                                @else
                                    Terjadi kesalahan dalam memproses status pembayaran. Silakan hubungi customer service.
                                @endif
                            </p>

                            @if($order)
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                                    <p class="text-sm text-red-800">
                                        <strong>Kode Pesanan:</strong> {{ $order->order_code }}<br>
                                        <strong>Status:</strong> {{ ucfirst($status) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-1 gap-3">
                        @if($isSuccess)
                            <a href="{{ route('orders.index') }}"
                                class="inline-flex items-center justify-center w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300">
                                <i class="fas fa-shopping-bag mr-2"></i> Lihat Pesanan Saya
                            </a>
                        @else
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-3 rounded-xl shadow-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
                                <i class="fas fa-redo mr-2"></i> Coba Lagi
                            </a>
                        @endif

                        <a href="{{ route('home') }}"
                            class="inline-flex items-center justify-center w-full bg-gray-500 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-gray-600 transition-all duration-300">
                            <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                        </a>
                    </div>

                    <!-- Support Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 text-center">
                            Butuh bantuan?
                            <a href="#" class="text-green-600 hover:text-green-700 font-medium">Hubungi Customer Service</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Info (Hanya tampil di environment local) - PERBAIKI INI -->
    @if(env('APP_ENV') === 'local')
        <div class="fixed bottom-4 right-4 bg-gray-800 text-white p-4 rounded-lg text-xs max-w-sm">
            <strong>Debug Info:</strong><br>
            Status: {{ $status }}<br>
            Display Status: {{ $displayStatus }}<br>
            Order ID: {{ $orderId ?? 'Not set' }}<br> <!-- PERBAIKI: tambahkan null coalescing -->
            Order Found: {{ $order ? 'Yes' : 'No' }}<br>
            Transaction Found: {{ $transaction ? 'Yes' : 'No' }}
        </div>
    @endif
@endsection