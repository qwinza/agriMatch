<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - FarmConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-yellow-50 to-amber-100 min-h-screen">
    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        <!-- Animated Illustration -->
        <div class="text-center mb-8">
            <div class="relative inline-block">
                <div
                    class="w-48 h-48 bg-gradient-to-br from-yellow-200 to-amber-300 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <div class="text-6xl">🚫</div>
                </div>
                <div class="absolute -top-2 -left-2 w-12 h-12 bg-red-200 rounded-full opacity-70 animate-bounce"></div>
            </div>
        </div>

        <!-- Error Content -->
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">
                <!-- Error Code -->
                <div class="mb-6">
                    <h1 class="text-8xl font-bold text-gray-800 mb-2">403</h1>
                    <div class="w-24 h-2 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-full mx-auto"></div>
                </div>

                <!-- Error Message -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-3">Akses Ditolak</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Halaman Tidak Ada atau Anda tidak memiliki izin untuk mengakses halaman ini.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <a href="{{ route('login') }}"
                        class="block w-full bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                        🔐 Login Kembali
                    </a>

                    <a href="{{ url('/') }}"
                        class="block w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                        🏠 Kembali ke Beranda
                    </a>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} FarmConnect. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>

</html>