<x-layout-component :title="'Pesanan ' . $order->order_number" description="Detail pesanan kamu." keywords="pesanan, order, checkout" author="My E-commerce Site">
    <section class="home-section cart-section">
        <div class="container-lg">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Keranjang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
                </ol>
            </nav>

            @include('admin.partials.success-error-info', ['type' => 'bootstrap'])

            <div class="home-section-head">
                <div>
                    <h2 class="home-section-title">Detail Pesanan</h2>
                    <p class="home-section-subtitle">No. {{ $order->order_number }} · {{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    <div class="cart-list">
                        @foreach ($order->order_items as $item)
                            @php
                                $product = $item->product;
                                $imageUrl = $product && $product->image && file_exists(public_path('images/products/' . $product->image))
                                    ? asset('images/products/' . $product->image)
                                    : asset('images/images.jpeg');
                                $subtotal = $item->quantity * $item->price;
                            @endphp

                            <div class="cart-item">
                                @if ($product)
                                    <a href="{{ route('products.show', $product->slug) }}" class="cart-item-media">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="cart-item-image">
                                    </a>
                                @else
                                    <div class="cart-item-media">
                                        <img src="{{ $imageUrl }}" alt="Produk tidak tersedia" class="cart-item-image">
                                    </div>
                                @endif

                                <div class="cart-item-body">
                                    @if ($product && $product->product_category)
                                        <span class="badge product-card-category align-self-start mb-2">
                                            {{ $product->product_category->name }}
                                        </span>
                                    @endif
                                    @if ($product)
                                        <a href="{{ route('products.show', $product->slug) }}" class="cart-item-title">
                                            {{ $product->name }}
                                        </a>
                                    @else
                                        <span class="cart-item-title">Produk tidak tersedia</span>
                                    @endif
                                    <p class="cart-item-meta">
                                        Rp {{ number_format($item->price, 0, ',', '.') }} &times; {{ $item->quantity }}
                                    </p>
                                </div>

                                <div class="cart-item-summary">
                                    <span class="cart-item-label">Subtotal</span>
                                    <span class="cart-item-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary-card">
                        <h3 class="cart-summary-title">Ringkasan Pesanan</h3>

                        @admin
                            @php
                                $paymentLabels = [
                                    'paid' => 'Lunas',
                                    'unpaid' => 'Belum dibayar',
                                    'refunded' => 'Dikembalikan',
                                ];
                            @endphp

                            <form action="{{ route('orders.update', $order) }}" method="POST" class="cart-summary-admin-form">
                                @csrf
                                @method('PATCH')

                                <div class="cart-summary-row">
                                    <span><label for="order-status">Status</label></span>
                                    <select name="status" id="order-status" class="form-select form-select-sm cart-summary-select">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="cart-summary-row">
                                    <span><label for="order-payment-status">Pembayaran</label></span>
                                    <select name="payment_status" id="order-payment-status" class="form-select form-select-sm cart-summary-select">
                                        @foreach ($paymentStatuses as $paymentStatus)
                                            <option value="{{ $paymentStatus }}" @selected(old('payment_status', $order->payment_status) === $paymentStatus)>
                                                {{ $paymentLabels[$paymentStatus] ?? ucfirst($paymentStatus) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn product-card-button w-100 mt-2">
                                    Simpan Perubahan
                                </button>
                            </form>
                        @else
                            <div class="cart-summary-row">
                                <span>Status</span>
                                <span>{{ ucfirst($order->status) }}</span>
                            </div>
                            <div class="cart-summary-row">
                                <span>Pembayaran</span>
                                <span>{{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum dibayar' }}</span>
                            </div>
                        @endadmin

                        <hr>

                        <div class="cart-summary-row cart-summary-total">
                            <span>Total</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>

                        <p class="cart-summary-note mt-3 mb-1"><strong>Alamat pengiriman</strong></p>
                        <p class="cart-summary-note">{{ $order->shipping_address }}</p>

                        <a href="{{ route('home') }}" class="btn product-card-button w-100 mt-3">Lanjut Belanja</a>

                        @if ($whatsappUrl)
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-success w-100 mt-2">
                                Konfirmasi via WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout-component>
