@extends('layouts.app')

@section('title', 'Login - AgriMatch')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Main Card -->
    <div class="max-w-md w-full space-y-8">
        <!-- Header Section -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                <span class="text-2xl font-bold text-white">A</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Selamat Datang</h2>
            <p class="mt-2 text-sm text-gray-600">
                Masuk ke akun AgriMatch Anda
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            required
                            placeholder="masukkan email Anda"
                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white"
                            value="{{ old('email') }}"
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            required
                            placeholder="masukkan kata sandi"
                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white"
                        >
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember"
                            name="remember"
                            class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="text-sm font-medium text-green-600 hover:text-green-500 transition">
                        Lupa kata sandi?
                    </a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200"
                >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-green-300 group-hover:text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </span>
                    Masuk ke Akun
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Atau lanjutkan dengan</span>
                    </div>
                </div>
            </div>

            <!-- Google Login Button -->
            <div class="mt-6">
                <a 
                    href="{{ route('google.redirect') }}"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200"
                >
                    <img src="{{ asset('images/search.png') }}" alt="Google" class="w-5 h-5">
                    <span>Login dengan Google</span>
                </a>
            </div>

            <!-- Registration Link -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-500 transition duration-200">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    /* Smooth transitions for all interactive elements */
    .transition {
        transition: all 0.2s ease-in-out;
    }

    /* Focus states for accessibility */
    input:focus, button:focus, a:focus {
        outline: none;
        ring: 2px;
    }

    /* Hover effects */
    .hover-lift:hover {
        transform: translateY(-1px);
    }

    /* Custom shadow for depth */
    .shadow-custom {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>

<!-- JavaScript for enhanced interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading state to login button
        const loginForm = document.querySelector('form');
        const loginButton = loginForm.querySelector('button[type="submit"]');
        
        loginForm.addEventListener('submit', function() {
            loginButton.disabled = true;
            loginButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...';
        });

        // Add floating label effect
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-green-500', 'ring-opacity-20');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-green-500', 'ring-opacity-20');
            });
        });
    });
</script>
@endsection