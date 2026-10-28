<x-layout-component :title="'Beli ' . $product->name" :description="$product->description" :keywords="$product->name . ', ' . $product->product_category->name" author="My E-commerce Site">
    @php
        $categoryName = $product->product_category->name;
        $imageUrl = $product->image && file_exists(public_path('images/products/' . $product->image))
            ? asset('images/products/' . $product->image)
            : asset('images/images.jpeg');
        $inStock = $product->stock > 0;
    @endphp

    {{-- Detail Produk --}}
    <section class="home-section product-detail">
        <div class="container-lg">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('/search?category=' . urlencode($categoryName)) }}">{{ $categoryName }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>

            <div class="row g-4 g-lg-5 align-items-start">
                <div class="col-lg-6">
                    <div class="product-detail-media">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="product-detail-image">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="product-detail-body">
                        <a href="{{ url('/search?category=' . urlencode($categoryName)) }}"
                           class="badge product-card-category text-decoration-none mb-3">{{ $categoryName }}</a>

                        <h1 class="product-detail-title">{{ $product->name }}</h1>

                        <div class="product-detail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                        @if ($inStock)
                            <span class="badge bg-success-subtle text-success-emphasis">Stok tersedia: {{ $product->stock }}</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger-emphasis">Stok habis</span>
                        @endif

                        <p class="product-detail-description">{{ $product->description }}</p>

                        <form class="product-detail-form" action="{{ url('/cart') }}" method="GET">
                            <div class="product-detail-qty">
                                <label for="quantity" class="form-label">Jumlah</label>
                                <input type="number" id="quantity" name="quantity" class="form-control"
                                       value="1" min="1" max="{{ max($product->stock, 1) }}" @disabled(! $inStock)>
                            </div>

                            <button type="submit" class="btn product-card-button btn-lg w-100" @disabled(! $inStock)>
                                {{ $inStock ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                            </button>
                        </form>

                        <a href="{{ url('/cart') }}" class="btn btn-outline-primary btn-lg w-100">Lihat Keranjang</a>

                        <div class="product-detail-perks mt-4">
                            <div class="product-detail-perk">
                                <span class="product-detail-perk-icon">🚚</span>
                                <div>
                                    <strong>Gratis Ongkir</strong>
                                    <p class="mb-0">Untuk pembelian di atas Rp 100.000.</p>
                                </div>
                            </div>
                            <div class="product-detail-perk">
                                <span class="product-detail-perk-icon">🔒</span>
                                <div>
                                    <strong>Pembayaran Aman</strong>
                                    <p class="mb-0">Beragam metode pembayaran terpercaya.</p>
                                </div>
                            </div>
                            <div class="product-detail-perk">
                                <span class="product-detail-perk-icon">↩️</span>
                                <div>
                                    <strong>Garansi 7 Hari</strong>
                                    <p class="mb-0">Retur mudah jika produk tidak sesuai.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk Terkait --}}
    @if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="home-section">
            <div class="container-lg">
                <div class="home-section-head">
                    <div>
                        <h2 class="home-section-title">Produk Terkait</h2>
                        <p class="home-section-subtitle">Produk lain dari kategori {{ $categoryName }}.</p>
                    </div>
                    <a href="{{ url('/search?category=' . urlencode($categoryName)) }}" class="btn btn-outline-primary">Lihat Semua</a>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                    @foreach ($relatedProducts as $related)
                        <div class="col">
                            <x-product-card
                                :image="asset('images/products/' . $related->image)"
                                :name="$related->name"
                                :description="$related->description"
                                :price="$related->price"
                                :category="$related->product_category->name"
                                :link="route('products.show', $related->slug)"
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
{{-- floating edit button for admin --}}
@admin
    <a href="{{ route('products.edit', $product) }}" style="position: fixed; bottom: 1rem; right: 1rem; background-color: #155dfa; color: white; padding: 0.5rem 1rem; border-radius: 9999px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); text-decoration: none;">Edit</a>
@endadmin
</x-layout-component>