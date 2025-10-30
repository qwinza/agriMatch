@extends('layouts.app')

@section('title', 'Daftar - AgriMatch')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Main Card - Lebar maksimum diperbesar -->
    <div class="max-w-lg w-full space-y-8 mt-20"> <!-- Changed from max-w-md to max-w-lg -->
        <!-- Header Section -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                <span class="text-2xl font-bold text-white">A</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Bergabung dengan AgriMatch</h2>
            <p class="mt-2 text-sm text-gray-600">
                Buat akun baru dan mulai jelajahi platform kami
            </p>
        </div>

        <!-- Register Card - Lebar penuh -->
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

            <!-- Register Form - Grid 2 kolom untuk beberapa field -->
            <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                @csrf

                <!-- Name Field - Full width -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            required
                            placeholder="Masukkan nama lengkap Anda"
                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white"
                            value="{{ old('name') }}"
                        >
                    </div>
                </div>

                <!-- Email Field - Full width -->
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
                            placeholder="contoh: nama@email.com"
                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white"
                            value="{{ old('email') }}"
                        >
                    </div>
                </div>

                <!-- Password Fields - Grid 2 kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                placeholder="Buat kata sandi"
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white"
                            >
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Min. 8 karakter</p>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                name="password_confirmation" 
                                required
                                placeholder="Ulangi kata sandi"
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white"
                            >
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Harus sama dengan sandi</p>
                    </div>
                </div>

                <!-- Role Selection - Lebar penuh dengan layout lebih baik -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Saya ingin mendaftar sebagai:
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Petani Option -->
                        <div class="relative">
                            <input 
                                type="radio" 
                                id="role_petani"
                                name="role" 
                                value="petani" 
                                class="hidden peer"
                                {{ old('role', 'petani') == 'petani' ? 'checked' : '' }}
                                required
                            >
                            <label for="role_petani" 
                                class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 transition duration-200 hover:border-gray-300 hover:bg-gray-50">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900 peer-checked:text-green-700">Petani</span>
                                    <span class="block text-xs text-gray-500 mt-1">Menjual produk pertanian</span>
                                </div>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>
                        </div>

                        <!-- Pembeli Option -->
                        <div class="relative">
                            <input 
                                type="radio" 
                                id="role_pembeli"
                                name="role" 
                                value="pembeli" 
                                class="hidden peer"
                                {{ old('role') == 'pembeli' ? 'checked' : '' }}
                            >
                            <label for="role_pembeli" 
                                class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 transition duration-200 hover:border-gray-300 hover:bg-gray-50">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900 peer-checked:text-green-700">Pembeli</span>
                                    <span class="block text-xs text-gray-500 mt-1">Membeli produk pertanian</span>
                                </div>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Terms Agreement -->
                <div class="flex items-start bg-gray-50 p-4 rounded-xl">
                    <div class="flex items-center h-5">
                        <input 
                            type="checkbox" 
                            id="terms"
                            name="terms"
                            required
                            class="focus:ring-green-500 h-4 w-4 text-green-500 border-gray-300 rounded"
                        >
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-600">
                            Dengan mendaftar, saya menyetujui 
                            <a href="#" class="font-medium text-green-600 hover:text-green-500 underline">Syarat & Ketentuan</a> 
                            dan 
                            <a href="#" class="font-medium text-green-600 hover:text-green-500 underline">Kebijakan Privasi</a>
                            AgriMatch
                        </label>
                    </div>
                </div>

                <!-- Register Button -->
                <button 
                    type="submit"
                    class="group relative w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-base font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 transform hover:-translate-y-0.5"
                >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-green-300 group-hover:text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </span>
                    Buat Akun Sekarang
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white text-gray-500">Atau daftar dengan</span>
                    </div>
                </div>
            </div>

            <!-- Google Register Button -->
            <div class="mt-6">
                <a 
                    href="{{ route('google.redirect') }}"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 hover:shadow-md"
                >
                    <img src="{{ asset('images/search.png') }}" alt="Google" class="w-5 h-5">
                    <span class="font-medium">Daftar dengan Google</span>
                </a>
            </div>

            <!-- Login Link -->
            <div class="mt-8 text-center p-4 bg-gray-50 rounded-xl">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-500 transition duration-200 ml-1">
                        Masuk di sini
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

    /* Enhanced hover effects */
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    /* Custom focus styles */
    input:focus, button:focus, a:focus {
        outline: none;
    }

    /* Animation for radio buttons */
    .peer:checked + label {
        transform: scale(1.02);
    }
</style>

<!-- JavaScript for enhanced interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading state to register button
        const registerForm = document.querySelector('form');
        const registerButton = registerForm.querySelector('button[type="submit"]');
        
        registerForm.addEventListener('submit', function() {
            registerButton.disabled = true;
            registerButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Membuat Akun...';
        });

        // Real-time password confirmation check
        const passwordInput = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        function checkPasswordMatch() {
            if (confirmPassword.value && passwordInput.value !== confirmPassword.value) {
                confirmPassword.classList.add('border-red-300', 'bg-red-50');
                confirmPassword.classList.remove('border-gray-300', 'bg-white');
            } else {
                confirmPassword.classList.remove('border-red-300', 'bg-red-50');
                confirmPassword.classList.add('border-gray-300', 'bg-white');
            }
        }

        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    });
</script>
@endsection