<nav class="navbar navbar-expand-lg navbar-dark site-navbar sticky-top">
  <div class="container-lg">
    <a class="navbar-brand fw-bold" href="{{ url('/') }}">
      <span class="me-1">🛍️</span>{{ config('app.name', 'Ecommerce') }}
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ url('/products') }}">Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('cart*') ? 'active' : '' }}" href="{{ url('/cart') }}">Keranjang</a>
        </li>
        @admin
        <li class="nav-item">
          <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        @endadmin
      </ul>
      <form class="d-flex site-navbar-search" action="{{ url('/search') }}" method="GET" role="search">
        <input class="form-control me-2" type="search" name="search" placeholder="Cari produk..." aria-label="Cari produk" value="{{ request('search') }}">
        <button class="btn btn-light" type="submit">Cari</button>
      </form>
      @auth
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
          </li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="nav-link btn btn-link" type="submit">Logout</button>
            </form>
          </li>
        </ul>
      @else
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">Register</a>
          </li>
        </ul>
      @endauth
    </div>
  </div>
</nav>