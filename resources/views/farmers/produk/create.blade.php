@extends('layouts.app')

@section('title', 'Tambah Produk - AgriMatch')

@section('content')
    <!-- Main Container dengan padding untuk navbar fixed -->
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            <!-- Header Section -->
            <div class="text-center mb-10 mt-20">
                <div class="flex items-center justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Produk Baru</h1>
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">
                    Mulai jual produk pertanian Anda dan jangkau lebih banyak pembeli
                </p>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-seedling text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Informasi Produk Baru</h2>
                            <p class="text-gray-600 text-sm">Isi semua informasi dengan benar untuk hasil terbaik</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Nama Produk -->
                                <div>
                                    <label for="nama_produk" class="block text-sm font-semibold text-gray-800 mb-3">
                                        Nama Produk <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tag text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="nama_produk" id="nama_produk"
                                            class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white shadow-sm"
                                            value="{{ old('nama_produk') }}" placeholder="Contoh: Wortel Organik Segar"
                                            required>
                                    </div>
                                    @error('nama_produk')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Kategori & Harga Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Kategori -->
                                    <div>
                                        <label for="kategori" class="block text-sm font-semibold text-gray-800 mb-3">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-layer-group text-gray-400 text-sm"></i>
                                            </div>
                                            <select name="kategori" id="kategori"
                                                class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 bg-white shadow-sm appearance-none"
                                                required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="sayuran" {{ old('kategori') == 'sayuran' ? 'selected' : '' }}>
                                                    Sayuran</option>
                                                <option value="buah" {{ old('kategori') == 'buah' ? 'selected' : '' }}>
                                                    Buah-buahan</option>
                                                <option value="beras" {{ old('kategori') == 'beras' ? 'selected' : '' }}>Beras
                                                    & Sereal</option>
                                                <option value="rempah" {{ old('kategori') == 'rempah' ? 'selected' : '' }}>
                                                    Rempah-rempah</option>
                                                <option value="umbi" {{ old('kategori') == 'umbi' ? 'selected' : '' }}>
                                                    Umbi-umbian</option>
                                                <option value="biji" {{ old('kategori') == 'biji' ? 'selected' : '' }}>Kacang
                                                    & Biji-bijian</option>
                                                <option value="herbal" {{ old('kategori') == 'herbal' ? 'selected' : '' }}>
                                                    Tanaman Herbal</option>
                                                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>
                                                    Lainnya</option>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-chevron-down text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('kategori')
                                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Harga -->
                                    <div>
                                        <label for="harga" class="block text-sm font-semibold text-gray-800 mb-3">
                                            Harga (Rp) <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-400 text-sm font-medium">Rp</span>
                                            </div>
                                            <input type="number" name="harga" id="harga"
                                                class="block w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white shadow-sm"
                                                value="{{ old('harga') }}" min="0" step="100" placeholder="25000" required>
                                        </div>
                                        @error('harga')
                                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Stok & Lokasi Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Stok -->
                                    <div>
                                        <label for="stok" class="block text-sm font-semibold text-gray-800 mb-3">
                                            Stok Tersedia <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-boxes text-gray-400 text-sm"></i>
                                            </div>
                                            <input type="number" name="stok" id="stok"
                                                class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white shadow-sm"
                                                value="{{ old('stok') }}" min="0" placeholder="100" required>
                                        </div>
                                        <p class="text-gray-500 text-xs mt-2">
                                            Jumlah produk yang tersedia untuk dijual
                                        </p>
                                        @error('stok')
                                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Lokasi -->
                                    <div>
                                        <label for="lokasi" class="block text-sm font-semibold text-gray-800 mb-3">
                                            Lokasi <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                                            </div>
                                            <input type="text" name="lokasi" id="lokasi"
                                                class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white shadow-sm"
                                                value="{{ old('lokasi') }}" placeholder="Bandung, Jawa Barat" required>
                                        </div>
                                        <p class="text-gray-500 text-xs mt-2">
                                            Kota/kabupaten tempat produk berada
                                        </p>
                                        @error('lokasi')
                                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <label for="deskripsi" class="block text-sm font-semibold text-gray-800 mb-3">
                                        Deskripsi Produk <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <textarea name="deskripsi" id="deskripsi" rows="6"
                                            class="block w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white shadow-sm resize-vertical"
                                            placeholder="Jelaskan detail produk Anda:
    • Kondisi dan kualitas produk
    • Cara budidaya atau panen
    • Keunggulan dan manfaat
    • Informasi penting lainnya" required>{{ old('deskripsi') }}</textarea>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-gray-500 text-xs">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Deskripsi yang jelas meningkatkan kepercayaan pembeli
                                        </p>
                                        <div id="charCounter" class="text-xs text-gray-500">0 karakter</div>
                                    </div>
                                    @error('deskripsi')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column - Foto Produk & Tips -->
                            <div class="space-y-6">
                                <!-- Foto Produk Section -->
                                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                    <label class="block text-sm font-semibold text-gray-800 mb-4">
                                        Foto Produk
                                    </label>

                                    <!-- File Upload Area -->
                                    <div class="mb-4">
                                        <input type="file" name="foto" id="foto" class="hidden" accept="image/*">
                                        <label for="foto"
                                            class="flex flex-col items-center justify-center w-full p-8 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition duration-200 bg-white">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                            <span class="text-sm font-medium text-gray-600">Klik untuk upload foto</span>
                                            <span class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Maks.
                                                2MB)</span>
                                            <span class="text-xs text-green-600 mt-2 font-medium">
                                                <i class="fas fa-camera mr-1"></i>Rekomendasi: foto produk yang jelas
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="hidden">
                                        <p class="text-sm font-medium text-gray-700 mb-3">Preview Foto:</p>
                                        <div class="flex justify-center">
                                            <div class="relative group">
                                                <img id="preview"
                                                    class="w-48 h-48 object-cover rounded-xl border-2 border-gray-300 shadow-md transition duration-200 group-hover:shadow-lg">
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition duration-200 flex items-center justify-center">
                                                    <span
                                                        class="text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition duration-200">
                                                        Preview
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-center text-xs text-gray-500 mt-2">
                                            Foto akan ditampilkan seperti ini
                                        </p>
                                    </div>

                                    @error('foto')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Tips Box -->
                                <div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-lightbulb text-blue-500 text-lg mt-1"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Produk Terbaik</h4>
                                            <ul class="text-xs text-blue-700 space-y-1.5">
                                                <li class="flex items-start">
                                                    <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                                                    Gunakan foto dengan pencahayaan baik
                                                </li>
                                                <li class="flex items-start">
                                                    <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                                                    Tulis deskripsi yang detail dan jujur
                                                </li>
                                                <li class="flex items-start">
                                                    <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                                                    Harga yang kompetitif menarik pembeli
                                                </li>
                                                <li class="flex items-start">
                                                    <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                                                    Update stok secara berkala
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Stats -->
                                <div class="bg-green-50 rounded-xl p-5 border border-green-200">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-chart-line text-green-500 text-lg mt-1"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-semibold text-green-800 mb-2">Manfaat Produk Berkualitas
                                            </h4>
                                            <ul class="text-xs text-green-700 space-y-1.5">
                                                <li>• Meningkatkan kepercayaan pembeli</li>
                                                <li>• Mempercepat proses penjualan</li>
                                                <li>• Membangun reputasi positif</li>
                                                <li>• Mendapatkan review yang baik</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-10 pt-8 border-t border-gray-200">
                            <div class="text-sm text-gray-500 flex items-center">
                                <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                Data Anda terlindungi dan aman
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <a href="{{ route('products.my-products') }}"
                                    class="inline-flex items-center justify-center px-6 py-3.5 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition duration-200 font-medium shadow-sm hover:shadow-md order-2 sm:order-1">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent rounded-xl shadow-sm text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 font-semibold transform hover:-translate-y-0.5 order-1 sm:order-2">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Produk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        /* Smooth transitions */
        .transition {
            transition: all 0.2s ease-in-out;
        }

        /* Custom scrollbar for textarea */
        textarea {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }

        textarea::-webkit-scrollbar {
            width: 6px;
        }

        textarea::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 3px;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* File upload hover effects */
        #foto+label:hover {
            border-color: #10b981;
            background-color: #ecfdf5;
            transform: translateY(-1px);
        }

        /* Custom select styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>

    <!-- JavaScript for enhanced functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // File upload preview
            const fileInput = document.getElementById('foto');
            const filePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('preview');

            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    // Check file size (2MB = 2 * 1024 * 1024 bytes)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        filePreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    filePreview.classList.add('hidden');
                }
            });

            // Form submission loading state
            const form = document.querySelector('form');
            const submitButton = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function () {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...';
            });

            // Auto-format harga input
            const hargaInput = document.getElementById('harga');
            hargaInput.addEventListener('input', function () {
                // Remove any non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Character counter for deskripsi
            const deskripsiTextarea = document.getElementById('deskripsi');
            const charCounter = document.getElementById('charCounter');

            function updateCharCounter() {
                const length = deskripsiTextarea.value.length;
                charCounter.textContent = `${length} karakter`;

                if (length > 200) {
                    charCounter.classList.add('text-green-600', 'font-medium');
                    charCounter.classList.remove('text-gray-500');
                } else {
                    charCounter.classList.remove('text-green-600', 'font-medium');
                    charCounter.classList.add('text-gray-500');
                }
            }

            deskripsiTextarea.addEventListener('input', updateCharCounter);
            updateCharCounter(); // Initial call

            // Auto-capitalize first letter of product name
            const namaProdukInput = document.getElementById('nama_produk');
            namaProdukInput.addEventListener('blur', function () {
                if (this.value) {
                    this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
                }
            });

            // Prevent negative values for stock
            const stokInput = document.getElementById('stok');
            stokInput.addEventListener('input', function () {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        });
    </script>
@endsection