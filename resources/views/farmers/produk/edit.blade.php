@extends('layouts.app')

@section('title', 'Edit Produk - AgriMatch')

@section('content')
    <!-- Tambahkan padding top untuk navbar fixed -->
    <div class="max-w-5xl mx-auto pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-edit text-white text-lg"></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Produk</h1>
            <p class="text-gray-600 mt-2 max-w-2xl mx-auto">
                Perbarui informasi produk pertanian Anda dengan data yang terbaru
            </p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-seedling text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $product->nama_produk }}</h2>
                        <p class="text-gray-600 text-sm">Terakhir diupdate: {{ $product->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form action="{{ route('products.update', $encryptedId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                        value="{{ old('nama_produk', $product->nama_produk) }}" 
                                        placeholder="Masukkan nama produk"
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
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-layer-group text-gray-400 text-sm"></i>
                                        </div>
                                        <select name="kategori" id="kategori"
                                            class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 bg-white shadow-sm appearance-none"
                                            required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="sayuran" {{ old('kategori', $product->kategori) == 'sayuran' ? 'selected' : '' }}>Sayuran</option>
                                            <option value="buah" {{ old('kategori', $product->kategori) == 'buah' ? 'selected' : '' }}>Buah-buahan</option>
                                            <option value="beras" {{ old('kategori', $product->kategori) == 'beras' ? 'selected' : '' }}>Beras</option>
                                            <option value="rempah" {{ old('kategori', $product->kategori) == 'rempah' ? 'selected' : '' }}>Rempah-rempah</option>
                                            <option value="umbi" {{ old('kategori', $product->kategori) == 'umbi' ? 'selected' : '' }}>Umbi-umbian</option>
                                            <option value="lainnya" {{ old('kategori', $product->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
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
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tag text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="number" name="harga" id="harga"
                                            class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white shadow-sm"
                                            value="{{ old('harga', $product->harga) }}" 
                                            min="0" step="100" 
                                            placeholder="0"
                                            required>
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
                                        Stok <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-boxes text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="number" name="stok" id="stok"
                                            class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white shadow-sm"
                                            value="{{ old('stok', $product->stok) }}" 
                                            min="0" 
                                            placeholder="0"
                                            required>
                                    </div>
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
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="lokasi" id="lokasi"
                                            class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 placeholder-gray-500 bg-white shadow-sm"
                                            value="{{ old('lokasi', $product->lokasi) }}" 
                                            placeholder="Contoh: Bandung, Jawa Barat"
                                            required>
                                    </div>
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
                                        placeholder="Jelaskan detail produk Anda, termasuk kualitas, keunggulan, dan informasi penting lainnya..."
                                        required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                                </div>
                                <p class="text-gray-500 text-xs mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Deskripsi yang jelas akan meningkatkan minat pembeli
                                </p>
                                @error('deskripsi')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column - Foto Produk -->
                        <div class="space-y-6">
                            <!-- Foto Produk Section -->
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <label class="block text-sm font-semibold text-gray-800 mb-4">
                                    Foto Produk
                                </label>

                                <!-- Preview Foto Saat Ini -->
                                @if($product->foto)
                                    <div class="mb-6">
                                        <p class="text-sm font-medium text-gray-700 mb-3">Foto saat ini:</p>
                                        <div class="flex justify-center">
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $product->foto) }}" 
                                                     alt="{{ $product->nama_produk }}"
                                                     class="w-48 h-48 object-cover rounded-xl border-2 border-gray-300 shadow-md transition duration-200 group-hover:shadow-lg">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition duration-200 flex items-center justify-center">
                                                    <span class="text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition duration-200">
                                                        Preview
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-center text-xs text-gray-500 mt-2">
                                            Klik gambar untuk melihat detail
                                        </p>
                                    </div>
                                @else
                                    <div class="mb-6 text-center py-8 bg-white rounded-lg border-2 border-dashed border-gray-300">
                                        <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                                        <p class="text-gray-500 text-sm">Tidak ada foto produk</p>
                                    </div>
                                @endif

                                <!-- File Input -->
                                <div>
                                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Foto Baru
                                    </label>
                                    <div class="relative">
                                        <input type="file" name="foto" id="foto"
                                            class="hidden"
                                            accept="image/*">
                                        <label for="foto" 
                                            class="flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition duration-200 bg-white">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                            <span class="text-sm font-medium text-gray-600">Klik untuk upload foto</span>
                                            <span class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                                        </label>
                                    </div>
                                    <p class="text-gray-500 text-xs mt-3 flex items-center">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Kosongkan jika tidak ingin mengubah foto
                                    </p>
                                    @error('foto')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- File Preview (akan diisi oleh JavaScript) -->
                                <div id="filePreview" class="hidden mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Preview foto baru:</p>
                                    <div class="flex justify-center">
                                        <img id="previewImage" class="w-32 h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                <div class="flex items-start">
                                    <i class="fas fa-lightbulb text-blue-500 mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Edit Produk</h4>
                                        <ul class="text-xs text-blue-700 space-y-1">
                                            <li>• Pastikan informasi produk akurat dan terbaru</li>
                                            <li>• Gunakan foto yang jelas dan menarik</li>
                                            <li>• Update stok secara berkala</li>
                                            <li>• Harga yang kompetitif meningkatkan penjualan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-10 pt-8 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-history mr-2"></i>
                            Terakhir diupdate: {{ $product->updated_at->diffForHumans() }}
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="{{ route('products.my-products') }}"
                                class="inline-flex items-center justify-center px-6 py-3.5 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition duration-200 font-medium shadow-sm hover:shadow-md">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent rounded-xl shadow-sm text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 font-semibold transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
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
        #foto + label:hover {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
    </style>

    <!-- JavaScript for enhanced functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File upload preview
            const fileInput = document.getElementById('foto');
            const filePreview = document.getElementById('filePreview');
            const previewImage = document.getElementById('previewImage');

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
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

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            });

            // Auto-format harga input
            const hargaInput = document.getElementById('harga');
            hargaInput.addEventListener('input', function() {
                // Remove any non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Add character counter for deskripsi
            const deskripsiTextarea = document.getElementById('deskripsi');
            const charCounter = document.createElement('div');
            charCounter.className = 'text-xs text-gray-500 mt-1 text-right';
            deskripsiTextarea.parentNode.appendChild(charCounter);

            function updateCharCounter() {
                const length = deskripsiTextarea.value.length;
                charCounter.textContent = `${length} karakter`;

                if (length > 500) {
                    charCounter.classList.add('text-green-600');
                    charCounter.classList.remove('text-gray-500');
                } else {
                    charCounter.classList.remove('text-green-600');
                    charCounter.classList.add('text-gray-500');
                }
            }

            deskripsiTextarea.addEventListener('input', updateCharCounter);
            updateCharCounter(); // Initial call
        });
    </script>
@endsection