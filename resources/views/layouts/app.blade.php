<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriMatch</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #map {
            width: 100%;
            height: 500px;
            border-radius: 1rem;
        }

        .swiper {
            width: 100%;
            padding: 30px 0;
        }

        .swiper-slide {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .swiper-slide:hover {
            transform: translateY(-5px);
        }

        .swiper-slide img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .swiper-slide .content {
            padding: 15px;
        }
    </style>
</head>

<body>

    @include('layouts.navbar')

    <main>
        @yield('content')
    </main>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ==== LEAFLET MAP ====
            const mapContainer = document.getElementById('map');
            if (mapContainer) {
                const map = L.map('map').setView([-2.5489, 118.0149], 5);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
                }).addTo(map);

                const farmers = [
                    { name: "Budi", crop: "Cabai", lat: -7.801389, lng: 110.364722 },
                    { name: "Siti", crop: "Padi", lat: -6.914744, lng: 107.609810 },
                    { name: "Andi", crop: "Jagung", lat: -8.409518, lng: 115.188919 },
                ];

                farmers.forEach(f => {
                    L.marker([f.lat, f.lng]).addTo(map)
                        .bindPopup(`<b>${f.name}</b><br>Petani ${f.crop}`);
                });
            }

            // ==== SWIPER CAROUSEL ====
            const swiperContainer = document.querySelector('.swiper');
            if (swiperContainer) {
                new Swiper('.swiper', {
                    slidesPerView: 4,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        320: { slidesPerView: 1 },
                        640: { slidesPerView: 2 },
                        1024: { slidesPerView: 4 },
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            }
        });
    </script>

</body>

</html>
