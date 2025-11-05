{{-- resources/views/layouts/navbar.blade.php --}}
<nav id="navbar"
  class="fixed w-full top-0 z-50 transition-all duration-300 
    {{ request()->is('/') && !request()->is('dashboard*', 'farmers*', 'buyers*', 'products*', 'orders*', 'petani*') ? 'bg-transparent text-white' : 'bg-white text-gray-800 shadow-md' }}">

  <div class="container mx-auto flex justify-between items-center py-4 px-6">

    <!-- Logo -->
    <a href="{{ route('home') }}"
      class="text-2xl font-bold 
        {{ request()->is('/') && !request()->is('dashboard*', 'farmers*', 'buyers*', 'products*', 'orders*', 'petani*') ? 'text-green-400' : 'text-green-600' }}">
      AgriMatch
    </a>

    <!-- Menu Tengah - Berbeda berdasarkan status auth & role -->
    <ul class="flex space-x-6">
      @auth
        <!-- Menu untuk user yang SUDAH LOGIN -->
        @if(auth()->user()->role === 'petani')
          <!-- Menu Petani - DIUPDATE -->
          <li><a href="{{ route('farmers.dashboard') }}"
              class="navbar-link hover:text-green-600 font-medium transition-colors {{ request()->is('farmers*') ? 'text-green-600 font-semibold' : '' }}">Dashboard</a>
          </li>
          <li><a href="{{ route('products.my-products') }}"
              class="navbar-link hover:text-green-600 font-medium transition-colors {{ request()->is('products/my-products*') ? 'text-green-600 font-semibold' : '' }}">Produk
              Saya</a></li>
          <li><a href="{{ route('orders.index') }}"
              class="navbar-link hover:text-green-600 font-medium transition-colors {{ request()->is('orders*') ? 'text-green-600 font-semibold' : '' }}">Pesanan</a>
          </li>
        @elseif(auth()->user()->role === 'pembeli')
          <!-- Menu Pembeli -->
          <li><a href="{{ route('buyers.dashboard') }}"
              class="navbar-link hover:text-green-600 font-medium transition-colors {{ request()->is('buyers*') ? 'text-green-600 font-semibold' : '' }}">Dashboard</a>
          </li>
          <li><a href="{{ route('products.index') }}"
              class="navbar-link hover:text-green-600 font-medium transition-colors {{ request()->is('products') ? 'text-green-600 font-semibold' : '' }}">Cari
              Produk</a></li>
          <li><a href="{{ route('orders.index') }}"
              class="navbar-link hover:text-green-600 font-medium transition-colors {{ request()->is('orders*') ? 'text-green-600 font-semibold' : '' }}">Pesanan
              Saya</a></li>
        @endif
      @else
        <!-- Menu untuk GUEST (belum login) -->
        <li><a href="#beranda" class="navbar-link hover:text-green-400 transition-colors">Beranda</a></li>
        <li><a href="#tentang" class="navbar-link hover:text-green-400 transition-colors">Tentang</a></li>
        <li><a href="#peta" class="navbar-link hover:text-green-400 transition-colors">Peta</a></li>
        <li><a href="#fitur" class="navbar-link hover:text-green-400 transition-colors">Fitur</a></li>
        <li><a href="#kontak" class="navbar-link hover:text-green-400 transition-colors">Kontak</a></li>
      @endauth
    </ul>

    <!-- Menu Kanan (Auth) -->
    <ul class="flex space-x-4 items-center">
      @guest
        <!-- Menu untuk guest -->
        <li>
          <a href="{{ route('login') }}"
            class="navbar-link border border-green-400 text-green-400 px-4 py-2 rounded-md hover:bg-green-400 hover:text-white transition font-medium">
            Masuk
          </a>
        </li>
        <li>
          <a href="{{ route('register') }}"
            class="navbar-link bg-green-400 text-white px-4 py-2 rounded-md hover:bg-green-500 transition font-medium">
            Daftar
          </a>
        </li>
      @else
        <li class="relative">
          <button class="navbar-link hover:text-green-600 relative p-2 transition-colors">
            <i class="fas fa-bell text-lg"></i>
          </button>
        </li>

        <!-- User Dropdown - DIUPDATE untuk Petani -->
        <li class="relative" id="userDropdown">
          <button id="dropdownTrigger"
            class="navbar-link flex items-center space-x-2 focus:outline-none py-2 transition-colors">
            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
              {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="font-medium">{{ auth()->user()->name }}</span>
            <i id="dropdownIcon" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
          </button>

          <!-- Dropdown Menu - DIUPDATE untuk Petani -->
          <ul id="dropdownMenu"
            class="absolute hidden bg-white text-gray-800 shadow-xl rounded-lg mt-2 right-0 min-w-48 py-2 border border-gray-100 z-50">
            @if(auth()->user()->role === 'petani')
              <li><a href="{{ route('farmers.dashboard') }}"
                  class="block px-4 py-2 hover:bg-green-50 font-medium transition-colors">
                  <i class="fas fa-tachometer-alt mr-2 text-green-500"></i>Dashboard
                </a>
              </li>
              <li><a href="{{ route('products.my-products') }}"
                  class="block px-4 py-2 hover:bg-green-50 font-medium transition-colors">
                  <i class="fas fa-seedling mr-2 text-green-500"></i>Produk Saya
                </a>
              </li>
              <li><a href="{{ route('orders.index') }}"
                  class="block px-4 py-2 hover:bg-green-50 font-medium transition-colors">
                  <i class="fas fa-shopping-cart mr-2 text-green-500"></i>Pesanan
                </a>
              </li>
            @elseif(auth()->user()->role === 'pembeli')
              <li><a href="{{ route('buyers.dashboard') }}"
                  class="block px-4 py-2 hover:bg-blue-50 font-medium transition-colors">
                  <i class="fas fa-tachometer-alt mr-2 text-blue-500"></i>Dashboard
                </a>
              </li>
              <li><a href="{{ route('products.index') }}"
                  class="block px-4 py-2 hover:bg-blue-50 font-medium transition-colors">
                  <i class="fas fa-search mr-2 text-blue-500"></i>Cari Produk
                </a>
              </li>
              <li><a href="{{ route('orders.index') }}"
                  class="block px-4 py-2 hover:bg-blue-50 font-medium transition-colors">
                  <i class="fas fa-receipt mr-2 text-blue-500"></i>Pesanan Saya
                </a>
              </li>
            @endif

            <!-- Menu Umum -->
            <li class="border-t border-gray-100 mt-2 pt-2">
              <a href="#" class="block px-4 py-2 hover:bg-gray-50 font-medium transition-colors">
                <i class="fas fa-user mr-2 text-gray-500"></i>Profil Saya
              </a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-50 font-medium transition-colors">
                <i class="fas fa-question-circle mr-2 text-gray-500"></i>Bantuan
              </a>
            </li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                  class="w-full text-left px-4 py-2 hover:bg-red-50 font-medium text-red-500 transition-colors">
                  <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                </button>
              </form>
            </li>
          </ul>
        </li>
      @endguest
    </ul>
  </div>
</nav>