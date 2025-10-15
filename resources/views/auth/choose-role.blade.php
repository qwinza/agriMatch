@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center" 
     style="background-image: url('{{ asset('images/bg-landing.jpg') }}');">

    <div class="bg-white/90 backdrop-blur-md shadow-lg rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-green-700 text-center mb-6">
            Pilih Peran Anda 🌾
        </h2>

        <form action="/choose-role" method="POST" class="space-y-4">
            @csrf

            <!-- Opsi Petani -->
            <label class="flex items-center justify-between border border-green-500 rounded-xl px-4 py-3 cursor-pointer hover:bg-green-50 transition">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="role" value="petani" required class="text-green-600 focus:ring-green-500">
                    <div>
                        <span class="block font-semibold text-green-700">Petani</span>
                        <span class="text-sm text-gray-600">Menjual hasil pertanian langsung ke pembeli</span>
                    </div>
                </div>
                <img src="{{ asset('images/farmer-icon.png') }}" alt="Petani" class="w-10 h-10">
            </label>

            <!-- Opsi Pembeli -->
            <label class="flex items-center justify-between border border-green-500 rounded-xl px-4 py-3 cursor-pointer hover:bg-green-50 transition">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="role" value="pembeli" required class="text-green-600 focus:ring-green-500">
                    <div>
                        <span class="block font-semibold text-green-700">Pembeli</span>
                        <span class="text-sm text-gray-600">Membeli produk pertanian langsung dari petani</span>
                    </div>
                </div>
                <img src="{{ asset('images/buyer-icon.png') }}" alt="Pembeli" class="w-10 h-10">
            </label>

            <button type="submit" 
                class="w-full bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700 transition mt-6">
                Lanjutkan
            </button>
        </form>
    </div>
</div>
@endsection
