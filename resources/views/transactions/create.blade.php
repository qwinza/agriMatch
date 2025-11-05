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
                    <form id="checkout-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="encryptedId" value="{{ $encryptedId }}">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                            <input type="text" name="recipient_name" id="recipient_name" required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Masukkan nama penerima">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Masukkan nomor telepon">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat Pengiriman</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                class="mt-1 block w-1/3 rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan Tambahan <span
                                    class="text-gray-400">(Opsional)</span></label>
                            <textarea name="notes" id="notes" rows="2"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Misal: Jangan diguncang, taruh di depan rumah"></textarea>
                        </div>

                        <div class="text-right text-lg font-bold text-gray-800">
                            Total: <span class="text-green-600" id="total-price">Rp
                                {{ number_format($product->harga, 0, ',', '.') }}</span>
                        </div>

                        <button type="button" id="pay-button"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300">
                            Lanjutkan ke Pembayaran
                        </button>

                        <a href="{{ url()->previous() }}"
                            class="block w-full text-center mt-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl py-2 font-medium transition-all duration-200">
                            Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Di transactions/create.blade.php --}}
    @section('scripts')
        @vite(['resources/js/checkout.js'])
    @endsection

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.clientKey') }}"></script>

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

            // Handle AJAX payment
            const payButton = document.getElementById('pay-button');
            const form = document.getElementById('checkout-form');

            payButton.addEventListener('click', function () {
                payButton.disabled = true;
                payButton.textContent = 'Memproses...';

                fetch("{{ route('transactions.pay') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        encryptedId: document.querySelector('input[name=encryptedId]').value,
                        recipient_name: document.getElementById('recipient_name').value,
                        phone: document.getElementById('phone').value,
                        shipping_address: document.getElementById('shipping_address').value,
                        quantity: document.getElementById('quantity').value,
                        notes: document.getElementById('notes').value
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.snapToken) {
                            snap.pay(data.snapToken, {
                                onSuccess: function (result) {
                                    window.location.href = "{{ route('transactions.finish', ['status' => 'success']) }}";
                                },
                                onPending: function (result) {
                                    window.location.href = "{{ route('transactions.finish', ['status' => 'pending']) }}";
                                },
                                onError: function (result) {
                                    window.location.href = "{{ route('transactions.finish', ['status' => 'error']) }}";
                                }
                            });
                        } else {
                            alert(data.message || 'Auto Payment Success.');
                            window.location.href = "{{ route('transactions.my-orders') }}";
                        }
                    })
                    .catch(() => alert('Terjadi kesalahan sistem.'))
                    .finally(() => {
                        payButton.disabled = false;
                        payButton.textContent = 'Lanjutkan ke Pembayaran';
                    });
            });
        });
    </script>
@endsection