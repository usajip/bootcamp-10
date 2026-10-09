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
          <a class="nav-link {{ request()->is('home-layout*') ? 'active' : '' }}" aria-current="page" href="{{ route('home.layout', ['id' => 1]) }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ url('/products') }}">Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('cart*') ? 'active' : '' }}" href="{{ url('/cart') }}">Keranjang</a>
        </li>
      </ul>
      <form class="d-flex site-navbar-search" action="{{ url('/products') }}" method="GET" role="search">
        <input class="form-control me-2" type="search" name="search" placeholder="Cari produk..." aria-label="Cari produk" value="{{ request('search') }}">
        <button class="btn btn-light" type="submit">Cari</button>
      </form>
    </div>
  </div>
</nav>