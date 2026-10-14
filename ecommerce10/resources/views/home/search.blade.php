<x-layout-component title="Hasil Pencarian - {{ $search }}" description="Belanja produk pilihan dengan harga terjangkau, pengiriman cepat, dan pembayaran aman." keywords="home, e-commerce, belanja online" author="My E-commerce Site">
    <section class="home-section">
        <div class="container-lg">
            @if(request('search'))
            <h2 class="home-section-title">Hasil Pencarian untuk "{{ $search }}"</h2>
            @endif
            {{-- Categories --}}
            <div class="mb-4">
                <h3>Kategori</h3>
                <ul class="list-inline">
                    @foreach ($categories as $categoryItem)
                        <li class="list-inline-item">
                            <a href="{{ url('/search?category=' . $categoryItem->name) }}" class="btn {{ request('category') == $categoryItem->name ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">{{ $categoryItem->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                @forelse ($products as $item)
                    <div class="col">
                        <x-product-card
                            :image="asset('images/images.jpeg')"
                            :name="$item->name"
                            :description="$item->description"
                            :price="$item->price"
                            :category="$item->product_category->name"
                            :link="url('/products')"
                        />
                    </div>
                @empty
                <div class="col">
                    <p>Tidak ada produk yang ditemukan.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
</x-layout-component>