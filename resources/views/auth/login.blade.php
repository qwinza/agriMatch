@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center" 
     style="background-image: url('{{ asset('images/bg-landing.jpg') }}');">

    <div class="bg-white/20 backdrop-blur-lg shadow-xl rounded-2xl p-8 w-full max-w-md text-white">
        <h2 class="text-3xl font-bold text-center mb-6">Masuk ke <span class="text-green-400">AgriMatch</span></h2>

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="bg-red-500/30 text-white text-sm rounded-md p-2 mb-4 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form Login --}}
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full p-2 rounded-lg border border-white/30 bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full p-2 rounded-lg border border-white/30 bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition">
                Login
            </button>

            {{-- Tombol Login Google --}}
            <a href="{{ route('google.redirect') }}"
               class="flex items-center justify-center gap-2 bg-white text-gray-800 font-medium py-2 rounded-lg hover:bg-gray-200 transition">
                <img src="{{ asset('images/search.png') }}" alt="Google" class="w-5 h-5">
                Login dengan Google
            </a>
        </form>

        <p class="mt-6 text-center text-sm text-gray-100">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-yellow-300 hover:underline">Daftar Sekarang</a>
        </p>
    </div>
</div>
@endsection
