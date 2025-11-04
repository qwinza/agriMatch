@extends('layouts.app')

@section('content')
    <div class="bg-light py-5" style="background: linear-gradient(180deg, #f7fff8 0%, #e9f9ec 100%); min-height: 100vh;">
        <div class="container">
            {{-- Judul Halaman --}}
            <h2 class="mb-5 text-center fw-bold text-success">
                🌾 Daftar Produk Hasil Tani
            </h2>

            {{-- Notifikasi sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm text-center" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Jika tidak ada produk --}}
            @if($products->isEmpty())
                <div class="text-center mt-5">
                    <img src="{{ asset('images/empty-box.png') }}" alt="Tidak ada produk" width="150" class="mb-3 opacity-75">
                    <p class="text-muted fs-5">Belum ada produk yang ditambahkan oleh petani.</p>
                </div>
            @else
                {{-- Grid Produk --}}
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 rounded-4 shadow-sm product-card overflow-hidden bg-white">
                                {{-- Gambar Produk --}}
                                <div class="ratio ratio-1x1 bg-light">
                                    <img src="{{ asset('storage/' . $product->foto) }}"
                                        class="card-img-top object-fit-cover rounded-top-4" alt="{{ $product->nama_produk }}">
                                </div>

                                {{-- Konten Produk --}}
                                <div class="card-body d-flex flex-column justify-content-between p-3">
                                    <div>
                                        <h5 class="text-success fw-bold mb-2">{{ $product->nama_produk }}</h5>
                                        <p class="text-muted small mb-3">
                                            {{ Str::limit($product->deskripsi, 60) }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="fw-bold text-dark fs-6 mb-3">
                                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                                        </p>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('products.show', $product->encryptedId) }}"
                                                class="btn btn-success fw-semibold rounded-3 shadow-sm">
                                                <i class="bi bi-info-circle"></i> Detail Produk
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Style tambahan --}}
    @push('styles')
        <style>
            body {
                background-color: #f7fff8 !important;
            }

            .product-card {
                transition: all 0.3s ease;
                border: 1px solid #e5f4e8;
            }

            .product-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
                border-color: #b4e1bc;
            }

            .card-footer {
                font-size: 0.9rem;
                background-color: #f9fffa !important;
            }

            .btn-success {
                background-color: #4caf50 !important;
                border-color: #4caf50 !important;
            }

            .btn-outline-success:hover {
                background-color: #4caf50 !important;
                color: white !important;
            }

            .text-success {
                color: #388e3c !important;
            }
        </style>
    @endpush
@endsection