@extends('layouts.app')

@section('title', 'Konfirmasi Pembelian')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 pt-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 pt-16">
                <h1 class="text-4xl font-bold text-gray-800 mb-3">Konfirmasi Pembelian 🛒</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Pastikan detail produk dan data pengiriman sudah benar sebelum melanjutkan ke pembayaran.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Detail Produk -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center">
                    <img src="{{ asset('storage/' . ($product->foto ?? 'images/default.jpg')) }}"
                        class="w-full max-h-72 object-cover rounded-xl mb-4" alt="{{ $product->nama_produk }}">
                    <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">{{ $product->nama_produk }}</h3>
                    <p class="text-gray-600 text-sm mb-2 text-center">{{ Str::limit($product->deskripsi, 150) }}</p>
                    <p class="text-lg font-bold text-green-600" id="product-price" data-price="{{ $product->harga }}">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Form Checkout -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <form action="{{ route('transactions.pay') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="encryptedId" value="{{ $encryptedId }}">

                        <!-- Nama Penerima -->
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700">Nama
                                Penerima</label>
                            <input type="text" name="recipient_name" id="recipient_name" required
                                class="mt-1 block w-full sm:w-full md:w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Masukkan nama penerima">
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" required
                                class="mt-1 block w-full sm:w-full md:w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Masukkan nomor telepon">
                        </div>

                        <!-- Alamat Pengiriman -->
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Alamat
                                Pengiriman</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required
                                class="mt-1 block w-full sm:w-full md:w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <!-- Jumlah Produk -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                class="mt-1 block w-full sm:w-full md:w-1/3 rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>

                        <!-- Catatan Tambahan -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Tambahan <span
                                    class="text-gray-400">(Opsional)</span></label>
                            <textarea name="notes" id="notes" rows="2"
                                class="mt-1 block w-full sm:w-full md:w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Misal: Jangan diguncang, taruh di depan rumah"></textarea>
                        </div>

                        <!-- Total Harga -->
                        <div class="text-right text-lg font-bold text-gray-800">
                            Total: <span class="text-green-600" id="total-price">Rp
                                {{ number_format($product->harga, 0, ',', '.') }}</span>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300">
                            Lanjutkan ke Pembayaran
                        </button>

                        <!-- Tombol Kembali -->
                        <a href="{{ url()->previous() }}"
                            class="block w-full text-center mt-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl py-2 font-medium transition-all duration-200">
                            Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityInput = document.getElementById('quantity');
            const totalPriceEl = document.getElementById('total-price');
            const productPrice = parseInt(document.getElementById('product-price').dataset.price);

            quantityInput.addEventListener('input', function () {
                let qty = parseInt(this.value);
                if (isNaN(qty) || qty < 1) qty = 1;
                this.value = qty;

                const total = qty * productPrice;
                totalPriceEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            });
        });
    </script>
@endsection