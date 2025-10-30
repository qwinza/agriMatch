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
        /* NAVBAR STYLES */
        #navbar {
            transition: all 0.3s ease-in-out;
        }
        
        .navbar-link {
            transition: all 0.2s ease-in-out;
        }
        
        /* Ensure dropdown stays on top */
        #dropdownMenu {
            z-index: 1000;
        }

        /* Original styles */
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
            // ===== NAVBAR DROPDOWN & SCROLL FUNCTIONALITY =====
            const dropdownTrigger = document.getElementById('dropdownTrigger');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownIcon = document.getElementById('dropdownIcon');
            let isDropdownOpen = false;

            // Dropdown functionality
            if (dropdownTrigger && dropdownMenu && dropdownIcon) {
                dropdownTrigger.addEventListener('click', function (e) {
                    e.stopPropagation();
                    isDropdownOpen = !isDropdownOpen;

                    if (isDropdownOpen) {
                        dropdownMenu.classList.remove('hidden');
                        dropdownIcon.classList.add('rotate-180');
                    } else {
                        dropdownMenu.classList.add('hidden');
                        dropdownIcon.classList.remove('rotate-180');
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!dropdownTrigger.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                        dropdownIcon.classList.remove('rotate-180');
                        isDropdownOpen = false;
                    }
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && isDropdownOpen) {
                        dropdownMenu.classList.add('hidden');
                        dropdownIcon.classList.remove('rotate-180');
                        isDropdownOpen = false;
                    }
                });

                dropdownMenu.addEventListener('click', function (e) {
                    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                        dropdownMenu.classList.add('hidden');
                        dropdownIcon.classList.remove('rotate-180');
                        isDropdownOpen = false;
                    }
                });
            }

            // ===== SCROLL FUNCTIONALITY FOR LANDING PAGE =====
            @if(request()->is('/') && !request()->is('dashboard*', 'farmers*', 'buyers*', 'products*', 'orders*', 'petani*'))
                const navbar = document.getElementById('navbar');
                const navbarLinks = document.querySelectorAll('.navbar-link');
                
                function updateNavbarOnScroll() {
                    if (window.scrollY > 50) {
                        // Scrolled - white background
                        navbar.classList.add('bg-white', 'text-gray-800', 'shadow-md');
                        navbar.classList.remove('bg-transparent', 'text-white');
                        
                        // Update logo color
                        const logo = navbar.querySelector('a[href="{{ route("home") }}"]');
                        if (logo) {
                            logo.classList.remove('text-green-400');
                            logo.classList.add('text-green-600');
                        }
                        
                        // Update all navbar links
                        navbarLinks.forEach(link => {
                            if (link.classList.contains('hover:text-green-400')) {
                                link.classList.remove('hover:text-green-400');
                                link.classList.add('hover:text-green-600');
                            }
                            if (link.classList.contains('text-green-400')) {
                                link.classList.remove('text-green-400');
                                link.classList.add('text-green-600');
                            }
                        });
                    } else {
                        // Top of page - transparent
                        navbar.classList.add('bg-transparent', 'text-white');
                        navbar.classList.remove('bg-white', 'text-gray-800', 'shadow-md');
                        
                        // Update logo color
                        const logo = navbar.querySelector('a[href="{{ route("home") }}"]');
                        if (logo) {
                            logo.classList.remove('text-green-600');
                            logo.classList.add('text-green-400');
                        }
                        
                        // Update all navbar links
                        navbarLinks.forEach(link => {
                            if (link.classList.contains('hover:text-green-600')) {
                                link.classList.remove('hover:text-green-600');
                                link.classList.add('hover:text-green-400');
                            }
                            if (link.classList.contains('text-green-600') && !link.classList.contains('font-semibold')) {
                                link.classList.remove('text-green-600');
                                link.classList.add('text-white');
                            }
                        });
                    }
                }

                // Initial check
                updateNavbarOnScroll();

                // Listen to scroll events
                window.addEventListener('scroll', updateNavbarOnScroll);
            @endif

            // ===== ORIGINAL MAP & SWIPER CODE =====
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