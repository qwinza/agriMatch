@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-100 to-white py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">💳 Checkout Produk</h2>

                <div class="flex items-center space-x-4 mb-6">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-32 h-32 rounded-lg object-cover">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                        <p class="text-green-600 font-bold text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <form id="payment-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" class="w-full border rounded-lg px-4 py-2" placeholder="Nama lengkap"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full border rounded-lg px-4 py-2"
                            placeholder="Alamat email" required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-gray-700">No. Telepon</label>
                        <input type="text" name="phone" class="w-full border rounded-lg px-4 py-2"
                            placeholder="08xxxxxxxxxx" required>
                    </div>

                    <button id="pay-button"
                        class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition-all">
                        Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Midtrans JS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        document.getElementById('pay-button').addEventListener('click', function (e) {
            e.preventDefault();

            let form = document.getElementById('payment-form');
            let formData = new FormData(form);

            fetch("{{ route('transaction.process') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            alert("Pembayaran berhasil!");
                            console.log(result);
                        },
                        onPending: function (result) {
                            alert("Menunggu pembayaran...");
                            console.log(result);
                        },
                        onError: function (result) {
                            alert("Terjadi kesalahan!");
                            console.log(result);
                        },
                        onClose: function () {
                            alert("Anda menutup jendela pembayaran.");
                        }
                    });
                })
                .catch(err => console.error(err));
        });
    </script>
@endsection