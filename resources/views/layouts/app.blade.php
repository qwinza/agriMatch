<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <a class="navbar-brand text-success fw-bold" href="{{ route('dashboard') }}">AgriMatch</a>

    <ul class="navbar-nav ms-auto">
        @guest
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Daftar</a></li>
        @else
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link">Keluar</button>
                </form>
            </li>
        @endguest
    </ul>
  </div>
</nav>
