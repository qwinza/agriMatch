@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<section id="beranda" class="relative h-screen flex items-center bg-cover bg-center"
    style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('{{ asset('images/bg-landing.jpg') }}');">
    <div class="container mx-auto px-6 md:px-12 lg:px-16">
        <div class="max-w-2xl text-white animate-fade-in">
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">
                Hubungkan <span class="text-green-400">Petani</span> &
                <span class="text-green-400">Pembeli</span><br>
                dengan Cara yang <span class="text-green-400">Mudah</span>
            </h1>
            <p class="text-lg text-gray-200 mb-8 leading-relaxed">
                AgriMatch adalah platform digital yang mempertemukan petani dan pembeli secara langsung,
                mendukung perdagangan hasil pertanian yang efisien, adil, dan berkelanjutan 🌱
            </p>
            <a href="{{ route('register') }}"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-3 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg">
                Mulai Sekarang
            </a>
        </div>
    </div>
</section>

<!-- TENTANG -->
<section id="tentang" class="relative py-28 bg-gradient-to-b from-green-50 via-white to-green-100 overflow-hidden">
    <div class="absolute top-0 left-0 w-40 h-40 bg-green-200 rounded-full opacity-30 blur-3xl animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-52 h-52 bg-green-300 rounded-full opacity-20 blur-3xl animate-pulse delay-700"></div>

    <div class="relative max-w-5xl mx-auto px-6 md:px-12 lg:px-16 text-center">
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-6">
            Tentang <span class="text-green-600">AgriMatch</span>
        </h2>

        <p class="text-lg md:text-xl text-gray-600 leading-relaxed mb-10 max-w-3xl mx-auto">
            AgriMatch adalah platform digital yang menghubungkan <span class="text-green-700 font-semibold">petani</span> dan
            <span class="text-green-700 font-semibold">pembeli</span> secara langsung.
            Kami menciptakan ekosistem pertanian yang <span class="text-green-600 font-semibold">transparan</span>,
            <span class="text-green-600 font-semibold">efisien</span>, dan <span class="text-green-600 font-semibold">berkelanjutan</span> 🌱
        </p>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8 mt-12">
            @foreach([
                    ['🌾', 'Langsung dari Petani', 'Petani dapat menjual hasil panen tanpa perantara dengan harga yang adil.'],
                    ['📊', 'Data Transparan', 'Pantau harga pasar dan permintaan produk dengan sistem berbasis data.'],
                    ['🚚', 'Distribusi Efisien', 'Proses pengiriman hasil pertanian lebih cepat dan tepat sasaran.']
                ] as [$icon, $title, $desc])
                <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-5xl mb-4">{{ $icon }}</div>
                    <h4 class="text-lg font-semibold text-green-700 mb-2">{{ $title }}</h4>
                    <p class="text-gray-600">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
        
        <div class="mt-16">
            <a href="{{ route('register') }}"
                class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-4 rounded-full shadow-md hover:shadow-lg transition-all duration-300">
                Bergabung Sekarang 🌱
            </a>
        </div>
    </div>
</section>

<!-- FITUR UTAMA -->
<section class="py-20 bg-gradient-to-b from-green-100 via-white to-green-50 text-center">
    <div class="container">
        <h2 class="fw-bold mb-4 text-4xl text-gray-800">
            Fitur <span class="text-green-600">Utama</span>
        </h2>
        <div class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('images/marketplace.jpg') }}" alt="Marketplace">
                    <div class="content">
                        <h5 class="fw-bold text-green-700">Marketplace Pertanian</h5>
                        <p>Jual dan beli hasil pertanian langsung antara petani dan pembeli.</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/analisis.jpg') }}" alt="Analisis">
                    <div class="content">
                        <h5 class="fw-bold text-green-700">Analisis Data</h5>
                        <p>Pantau tren harga, permintaan, dan panen secara real-time.</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/connect.jpg') }}" alt="Konektivitas">
                    <div class="content">
                        <h5 class="fw-bold text-green-700">Konektivitas Langsung</h5>
                        <p>Bangun hubungan bisnis jangka panjang dengan pembeli terpercaya.</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/logistic.jpg') }}" alt="Distribusi">
                    <div class="content">
                        <h5 class="fw-bold text-green-700">Distribusi Efisien</h5>
                        <p>Proses pengiriman hasil pertanian lebih cepat dan tepat sasaran.</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/community.jpg') }}" alt="Komunitas">
                    <div class="content">
                        <h5 class="fw-bold text-green-700">Komunitas Petani</h5>
                        <p>Bergabung dengan jaringan petani dan pembeli di seluruh Indonesia.</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/monitoring.jpg') }}" alt="Monitoring">
                    <div class="content">
                        <h5 class="fw-bold text-green-700">Monitoring</h5>
                        <p>Monitoring cuaca, lahan, dan kondisi panen untuk hasil maksimal.</p>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>

<!-- PETA -->
<section class="py-20 text-center bg-gradient-to-b from-white to-green-50">
    <div class="container">
        <h2 class="text-4xl font-bold text-gray-800 mb-6">
            Sebaran <span class="text-green-600">Petani</span>
        </h2>
        <p class="text-gray-600 mb-6">Lihat lokasi petani yang telah bergabung di seluruh Indonesia 🌾</p>
        <div id="map" class="w-full h-[500px] rounded-2xl shadow-md border"></div>
    </div>
</section>

<!-- MENGAPA -->
<section id="kenapa" class="py-24 bg-gradient-to-b from-green-50 via-white to-green-100 text-center">
   <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-4xl font-bold text-gray-800 mb-12">
            Mengapa Memilih <span class="text-green-600">AgriMatch?</span>
        </h2>
        <div class="grid md:grid-cols-3 gap-10">
            @foreach([
                    ['fair-price.jpg', 'Harga Adil', 'Petani menerima harga yang pantas tanpa potongan perantara.'],
                    ['easy-access.jpg', 'Akses Mudah', 'Platform dapat diakses kapan saja dari perangkat apa pun.'],
                    ['sustain.jpg', 'Berkelanjutan', 'Mendukung pertanian ramah lingkungan dan distribusi efisien.']
                ] as [$img, $title, $desc])
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl overflow-hidden transition-all duration-300 group">
                    <div class="h-40 bg-cover bg-center transition-transform duration-300 group-hover:scale-105"
                         style="background-image: url('{{ asset('images/' . $img) }}');"></div>
                    <div class="p-6">
                        <h4 class="font-semibold text-lg text-green-700 mb-2">{{ $title }}</h4>
                        <p class="text-gray-600">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section id="kontak" class="py-24 bg-gradient-to-r from-green-600 to-green-500 text-white text-center">
    <h2 class="text-4xl font-bold mb-4">Siap Bergabung dengan <span class="text-white">AgriMatch?</span></h2>
    <p class="mb-8 text-lg text-green-100">Bangun koneksi dan transaksi langsung antara petani & pembeli. 🌾</p>
    <a href="{{ route('register') }}"
        class="bg-white text-green-700 font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-gray-100 transition-all duration-300">
        Daftar Sekarang
    </a>
</section>

@endsection
