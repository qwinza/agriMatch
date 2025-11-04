@extends('layouts.app')

@section('title', 'Selesai Pembayaran')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center pt-16">
        <div class="container mx-auto px-4">
            <div class="max-w-lg mx-auto text-center">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    @if($status == 'success')
                        <div class="mb-6">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil!</h2>
                            <p class="text-gray-600">Terima kasih, pesanan Anda telah tercatat dan akan segera diproses.</p>
                        </div>
                    @else
                        <div class="mb-6">
                            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Status Pembayaran: {{ ucfirst($status) }}</h2>
                            <p class="text-gray-600">Harap cek kembali atau hubungi layanan pelanggan jika ada masalah.</p>
                        </div>
                    @endif

                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300">
                        <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection