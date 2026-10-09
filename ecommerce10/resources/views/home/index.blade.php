<x-layout-component title="Home Page" description="Belanja produk pilihan dengan harga terjangkau, pengiriman cepat, dan pembayaran aman." keywords="home, e-commerce, belanja online" author="My E-commerce Site">
    {{-- Hero --}}
    <section class="home-hero">
        <div class="container-lg">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <span class="badge home-hero-badge">🛍️ Belanja Mudah &amp; Aman</span>
                    <h1 class="home-hero-title">Temukan Produk Terbaik untuk Kebutuhan Sehari-hari</h1>
                    <p class="home-hero-text">
                        Ribuan produk pilihan dengan harga terjangkau, pengiriman cepat, dan garansi resmi
                        untuk pengalaman belanja yang menyenangkan.
                    </p>

                    <form class="home-hero-search" action="{{ url('/products') }}" method="GET" role="search">
                        <input type="search" name="search" class="form-control" placeholder="Cari produk favoritmu..." aria-label="Cari produk">
                        <button type="submit" class="btn btn-light">Cari</button>
                    </form>

                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ url('/products') }}" class="btn btn-light btn-lg px-4">Belanja Sekarang</a>
                        <a href="#kategori" class="btn btn-outline-light btn-lg px-4">Jelajahi Kategori</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="home-hero-media">
                        <img src="{{ asset('images/images.jpeg') }}" alt="Produk pilihan" class="home-hero-image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Keunggulan --}}
    <section class="home-section">
        <div class="container-lg">
            <div class="row g-4">
                @foreach ($features as $feature)
                    <div class="col-6 col-lg-3">
                        <div class="home-feature h-100">
                            <div class="home-feature-icon">{{ $feature['icon'] }}</div>
                            <h3 class="home-feature-title">{{ $feature['title'] }}</h3>
                            <p class="home-feature-text mb-0">{{ $feature['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="home-section" id="kategori">
        <div class="container-lg">
            <div class="home-section-head">
                <div>
                    <h2 class="home-section-title">Kategori Pilihan</h2>
                    <p class="home-section-subtitle">Temukan produk berdasarkan kategori favoritmu.</p>
                </div>
                <a href="{{ url('/products') }}" class="btn btn-outline-primary">Lihat Semua Kategori</a>
            </div>

            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ url('/products?category=' . urlencode($category['name'])) }}" class="home-category h-100">
                            <span class="home-category-icon">{{ $category['icon'] }}</span>
                            <span class="home-category-name">{{ $category['name'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Produk Terlaris --}}
    <section class="home-section" id="produk">
        <div class="container-lg">
            <div class="home-section-head">
                <div>
                    <h2 class="home-section-title">Produk Terlaris</h2>
                    <p class="home-section-subtitle">Produk paling banyak dibeli pelanggan kami.</p>
                </div>
                <a href="{{ url('/products') }}" class="btn btn-outline-primary">Lihat Semua Produk</a>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <x-product-card
                            :image="asset('images/images.jpeg')"
                            :name="$product['name']"
                            :description="$product['description']"
                            :price="$product['price']"
                            :category="$product['category']"
                            :link="url('/products')"
                        />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Promo --}}
    <section class="home-section">
        <div class="container-lg">
            <div class="home-promo">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="badge home-promo-badge">Promo Bulan Ini</span>
                        <h2 class="home-promo-title">Diskon hingga 50% untuk Produk Pilihan</h2>
                        <p class="home-promo-text mb-0">
                            Gunakan kode <strong>BELANJA50</strong> saat checkout dan nikmati potongan harga
                            spesial untuk pembelian pertamamu.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ url('/products') }}" class="btn btn-light btn-lg px-4">Klaim Promo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Newsletter --}}
    <section class="home-section">
        <div class="container-lg">
            <div class="home-newsletter">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <h2 class="home-newsletter-title">Dapatkan Info Promo Terbaru</h2>
                        <p class="home-newsletter-text mb-0">
                            Berlangganan newsletter kami dan jadilah yang pertama tahu soal diskon serta produk baru.
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <form class="home-newsletter-form" action="{{ url('/') }}" method="GET">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Alamat email kamu" aria-label="Alamat email" required>
                            <button type="submit" class="btn btn-primary btn-lg">Berlangganan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout-component>