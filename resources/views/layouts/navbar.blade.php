<nav id="navbar" class="fixed w-full top-0 z-50 bg-transparent text-white transition-all duration-300">
  <div class="container mx-auto flex justify-between items-center py-4 px-6">
    
    <!-- Logo -->
    <a href="{{ route('home') }}" class="text-2xl font-bold text-green-400">
      AgriMatch
    </a>

    <!-- Menu Tengah -->
    <ul class="flex space-x-6">
      <li><a href="#beranda" class="hover:text-green-400">Beranda</a></li>
      <li><a href="#tentang" class="hover:text-green-400">Tentang</a></li>
      <li><a href="#fitur" class="hover:text-green-400">Fitur</a></li>
      <li><a href="#kontak" class="hover:text-green-400">Kontak</a></li>
    </ul>

    <!-- Menu Kanan (Auth) -->
    <ul class="flex space-x-4">
      @guest
        <li>
          <a href="{{ route('login') }}" 
             class="border border-green-400 text-green-400 px-4 py-2 rounded-md hover:bg-green-400 hover:text-white transition">
             Masuk
          </a>
        </li>
        <li>
          <a href="{{ route('register') }}" 
             class="bg-green-400 text-white px-4 py-2 rounded-md hover:bg-green-500 transition">
             Daftar
          </a>
        </li>
      @else
        <li class="relative group">
          <button class="hover:text-green-400 focus:outline-none">
            Dashboard ▼
          </button>
          <ul class="absolute hidden group-hover:block bg-white text-gray-800 shadow-lg rounded-md mt-2 right-0">
            <li><a href="{{ route('farmers.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Petani</a></li>
            <li><a href="{{ route('buyers.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Pembeli</a></li>
          </ul>
        </li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="hover:text-green-400">Keluar</button>
          </form>
        </li>
      @endguest
    </ul>
  </div>
</nav>

<!-- Tambahkan script di bawah sebelum penutup body -->
<script>
  window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
      navbar.classList.add('bg-white', 'text-gray-800', 'shadow-md');
      navbar.classList.remove('bg-transparent', 'text-white');
    } else {
      navbar.classList.add('bg-transparent', 'text-white');
      navbar.classList.remove('bg-white', 'text-gray-800', 'shadow-md');
    }
  });
</script>
