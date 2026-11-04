<x-layout-component :title="'Keranjang Belanja'" description="Daftar produk di keranjang belanja kamu." keywords="keranjang, cart, belanja" author="My E-commerce Site">
    <section class="home-section cart-section">
        <div class="container-lg">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
                </ol>
            </nav>

            @include('admin.partials.success-error-info', ['type' => 'bootstrap'])

            <div class="home-section-head">
                <div>
                    <h2 class="home-section-title">Keranjang Belanja</h2>
                    <p class="home-section-subtitle">
                        {{ $totalQuantity }} produk di keranjang {{ Auth::user()->name }}.
                    </p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">Lanjut Belanja</a>
            </div>

            @if ($cartItems->isEmpty())
                <div class="cart-empty">
                    <span class="cart-empty-icon">🛒</span>
                    <h3 class="cart-empty-title">Keranjang kamu masih kosong</h3>
                    <p class="cart-empty-text">Yuk cari produk favoritmu dan tambahkan ke keranjang.</p>
                    <a href="{{ route('home') }}" class="btn product-card-button">Mulai Belanja</a>
                </div>
            @else
                <div class="row g-4 align-items-start">
                    <div class="col-lg-8">
                        <div class="cart-list">
                            @foreach ($cartItems as $item)
                                @php
                                    $product = $item->product;
                                    $imageUrl = $product->image && file_exists(public_path('images/products/' . $product->image))
                                        ? asset('images/products/' . $product->image)
                                        : asset('images/images.jpeg');
                                    $subtotal = $item->quantity * $product->price;
                                @endphp

                                <div class="cart-item">
                                    <a href="{{ route('products.show', $product->slug) }}" class="cart-item-media">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="cart-item-image">
                                    </a>

                                    <div class="cart-item-body">
                                        <span class="badge product-card-category align-self-start mb-2">
                                            {{ $product->product_category->name }}
                                        </span>
                                        <a href="{{ route('products.show', $product->slug) }}" class="cart-item-title">
                                            {{ $product->name }}
                                        </a>
                                        <p class="cart-item-meta">
                                            Rp {{ number_format($product->price, 0, ',', '.') }} &times; {{ $item->quantity }}
                                        </p>

                                        @if ($item->quantity > $product->stock)
                                            <p class="cart-item-warning">
                                                Jumlah melebihi stok tersedia ({{ $product->stock }}).
                                            </p>
                                        @endif
                                    </div>

                                    <div class="cart-item-summary">
                                        <span class="cart-item-label">Subtotal</span>
                                        <span class="cart-item-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>

                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="cart-item-form">
                                            @csrf
                                            @method('PATCH')
                                            <label for="quantity-{{ $item->id }}" class="cart-item-label d-block">Jumlah</label>
                                            <div class="input-group input-group-sm cart-item-qty-group">
                                                <input type="number" id="quantity-{{ $item->id }}" name="quantity"
                                                       class="form-control" value="{{ $item->quantity }}"
                                                       min="1" max="{{ max($product->stock, 1) }}" required>
                                                <button type="submit" class="btn btn-outline-primary">Perbarui</button>
                                            </div>
                                        </form>

                                        <form action="{{ route('cart.destroy', $item) }}" method="POST" class="cart-item-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link cart-item-remove"
                                                    onclick="return confirm('Hapus produk ini dari keranjang?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-summary-card">
                            <h3 class="cart-summary-title">Ringkasan Belanja</h3>

                            <div class="cart-summary-row">
                                <span>Total jumlah</span>
                                <span>{{ $totalQuantity }} produk</span>
                            </div>
                            <div class="cart-summary-row">
                                <span>Total harga</span>
                                <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>

                            <hr>

                            <div class="cart-summary-row cart-summary-total">
                                <span>Total</span>
                                <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>

                            <p class="cart-summary-note">Ongkos kirim dihitung saat checkout.</p>

                            <form action="{{ route('checkout.store') }}" method="POST">
                                @csrf
                                <label for="shipping_address" class="form-label">Alamat pengiriman</label>
                                <textarea id="shipping_address" name="shipping_address" rows="3"
                                    class="form-control @error('shipping_address') is-invalid @enderror @error('cart') is-invalid @enderror"
                                    required minlength="10" maxlength="1000"
                                    placeholder="Nama penerima, alamat lengkap, kota, dan kode pos">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('cart')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <button type="submit" class="btn product-card-button w-100 mt-3">Checkout Sekarang</button>
                            </form>

                            <a href="{{ route('home') }}" class="btn btn-outline-primary w-100 mt-2">Lanjut Belanja</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-layout-component>
