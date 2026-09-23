        // 50 Products Dataset
        const rawProducts = [
            // Elektronik (12 items)
            { id: 1, name: "Smartphone Galaxy X Pro 5G", category: "Elektronik", price: 4299000, originalPrice: 4800000, rating: 4.8, reviews: 128, description: "Layar AMOLED 120Hz, kamera jernih 108MP, Baterai 5000mAh pengisian cepat 67W.", image: "https://picsum.photos/seed/phone101/400/300" },
            { id: 2, name: "Laptop Ultrabook Thin 14 Inch", category: "Elektronik", price: 9899000, originalPrice: 10500000, rating: 4.9, reviews: 85, description: "Prosesor Intel Core i7 generasi terbaru, RAM 16GB SSD 512GB NVMe super cepat.", image: "https://picsum.photos/seed/laptop102/400/300" },
            { id: 3, name: "Headphone Wireless Active Noise Cancelling", category: "Elektronik", price: 1250000, originalPrice: 1500000, rating: 4.7, reviews: 210, description: "Suara bass mendalam, peredam kebisingan ANC, baterai bertahan hingga 30 jam.", image: "https://picsum.photos/seed/headphone103/400/300" },
            { id: 4, name: "Smartwatch Fitness Tracker GPS", category: "Elektronik", price: 850000, originalPrice: 990000, rating: 4.5, reviews: 94, description: "Pemantau detak jantung 24/7, layar HD Touchscreen, tahan air sertifikasi 5ATM.", image: "https://picsum.photos/seed/watch104/400/300" },
            { id: 5, name: "TWS Earbuds Bluetooth 5.3", category: "Elektronik", price: 349000, originalPrice: 499000, rating: 4.4, reviews: 320, description: "Koneksi stabil tanpa delay, latensi rendah untuk gaming, mikrofon ganda jernih.", image: "https://picsum.photos/seed/earbuds105/400/300" },
            { id: 6, name: "Kamera Mirrorless Vlogging 4K", category: "Elektronik", price: 8750000, originalPrice: 9200000, rating: 4.8, reviews: 42, description: "Kamera ringan khusus pembuat konten, perekaman video 4K 60fps dengan mic eksternal.", image: "https://picsum.photos/seed/camera106/400/300" },
            { id: 7, name: "Keyboard Mekanikal RGB Wireless", category: "Elektronik", price: 620000, originalPrice: 750000, rating: 4.6, reviews: 175, description: "Switch mekanikal empuk, lampu RGB dapat disesuaikan, koneksi bluetooth & dongle.", image: "https://picsum.photos/seed/keyboard107/400/300" },
            { id: 8, name: "Mouse Gaming Ergonomis 16000 DPI", category: "Elektronik", price: 280000, originalPrice: 350000, rating: 4.5, reviews: 140, description: "Sensor presisi tinggi, tombol makro yang bisa diprogram, desain nyaman di tangan.", image: "https://picsum.photos/seed/mouse108/400/300" },
            { id: 9, name: "Monitor Gaming Lengkung 144Hz 27 Inch", category: "Elektronik", price: 3100000, originalPrice: 3500000, rating: 4.8, reviews: 98, description: "Panel IPS sudut pandang luas, response time 1ms, mendukung FreeSync.", image: "https://picsum.photos/seed/monitor109/400/300" },
            { id: 10, name: "Powerbank Fast Charging 20000mAh", category: "Elektronik", price: 275000, originalPrice: 350000, rating: 4.7, reviews: 510, description: "Mendukung PD 22.5W, port Type-C & USB-A, indikator daya LED akurat.", image: "https://picsum.photos/seed/powerbank110/400/300" },
            { id: 11, name: "Speaker Bluetooth Portable Waterproof", category: "Elektronik", price: 480000, originalPrice: 600000, rating: 4.6, reviews: 88, description: "Suara stereo 360 derajat, baterai 12 jam, standar tahan air IPX7.", image: "https://picsum.photos/seed/speaker111/400/300" },
            { id: 12, name: "Tablet Drawing Grafis Digital", category: "Elektronik", price: 1450000, originalPrice: 1700000, rating: 4.7, reviews: 63, description: "Area menggambar luas, sensitivitas tekanan 8192 level, tanpa baterai pen.", image: "https://picsum.photos/seed/tablet112/400/300" },

            // Pakaian Pria (10 items)
            { id: 13, name: "Kemeja Polos Catton Premium Lengan Panjang", category: "Pakaian Pria", price: 165000, originalPrice: 220000, rating: 4.6, reviews: 230, description: "Bahan katun halus, tidak panas, cocok untuk acara formal maupun santai.", image: "https://picsum.photos/seed/shirt113/400/300" },
            { id: 14, name: "Jaket Denim Casual Regular Fit", category: "Pakaian Pria", price: 285000, originalPrice: 350000, rating: 4.7, reviews: 155, description: "Bahan jeans tebal berkualitas, warna tahan lama, desain klasik timeless.", image: "https://picsum.photos/seed/jacket114/400/300" },
            { id: 15, name: "Kaos Oversize Streetwear Combed 24s", category: "Pakaian Pria", price: 89000, originalPrice: 120000, rating: 4.5, reviews: 410, description: "Kain adem menyerap keringat, potong gaya kekinian, cocok sehari-hari.", image: "https://picsum.photos/seed/tshirt115/400/300" },
            { id: 16, name: "Celana Chino Slim Fit Stretch", category: "Pakaian Pria", price: 195000, originalPrice: 240000, rating: 4.6, reviews: 180, description: "Bahan melar fleksibel, jahitan rapi kuat, memberikan kesan ramping.", image: "https://picsum.photos/seed/pants116/400/300" },
            { id: 17, name: "Hoodie Polos Fleece Soft Warm", category: "Pakaian Pria", price: 175000, originalPrice: 230000, rating: 4.8, reviews: 290, description: "Bahan dalam halus hangat, kantong depan luas, tali kupluk tebal.", image: "https://picsum.photos/seed/hoodie117/400/300" },
            { id: 18, name: "Celana Jeans Denim Stretch Modern", category: "Pakaian Pria", price: 235000, originalPrice: 300000, rating: 4.7, reviews: 125, description: "Warna washed trendy, fleksibel dipakai bergerak bebas seharian.", image: "https://picsum.photos/seed/jeans118/400/300" },
            { id: 19, name: "Kemeja Batik Modern Motifs Ethnic", category: "Pakaian Pria", price: 185000, originalPrice: 250000, rating: 4.9, reviews: 88, description: "Batik katun print berkualitas tinggi, warna tegas dan tidak luntur.", image: "https://picsum.photos/seed/batik119/400/300" },
            { id: 20, name: "Rompi Windbreaker Outdoor Casual", category: "Pakaian Pria", price: 145000, originalPrice: 190000, rating: 4.4, reviews: 67, description: "Bahan parasit tahan angin, dilengkapi banyak saku fungsional.", image: "https://picsum.photos/seed/vest120/400/300" },
            { id: 21, name: "Kaos Polo Shirt Pique Cotton", category: "Pakaian Pria", price: 115000, originalPrice: 150000, rating: 4.6, reviews: 195, description: "Kerah kokoh rapi, bahan berpori nyaman dan tidak gerah.", image: "https://picsum.photos/seed/polo121/400/300" },
            { id: 22, name: "Celana Pendek Cargo Ripstop", category: "Pakaian Pria", price: 125000, originalPrice: 160000, rating: 4.5, reviews: 142, description: "Bahan ripstop serat anti sobek, pas untuk kegiatan santai & outdoor.", image: "https://picsum.photos/seed/cargoshorts122/400/300" },

            // Pakaian Wanita (10 items)
            { id: 23, name: "Dress Floral Vintage Chiffon", category: "Pakaian Wanita", price: 215000, originalPrice: 280000, rating: 4.8, reviews: 165, description: "Bahan siffon jatuh anggun, dilapisi furing lembut, motif bunga cantik.", image: "https://picsum.photos/seed/dress123/400/300" },
            { id: 24, name: "Blouse Wanita Elegance Silk Look", category: "Pakaian Wanita", price: 140000, originalPrice: 180000, rating: 4.7, reviews: 110, description: "Kesan mewah mengkilap lembut, cocok dipadukan kulot atau rok.", image: "https://picsum.photos/seed/blouse124/400/300" },
            { id: 25, name: "Celana Kulot High Waist Knit", category: "Pakaian Wanita", price: 135000, originalPrice: 170000, rating: 4.6, reviews: 340, description: "Bahan rajut elastis jatuh, membuat kaki terlihat lebih jenjang.", image: "https://picsum.photos/seed/culottes125/400/300" },
            { id: 26, name: "Cardigan Rajut Oversize Cable Knit", category: "Pakaian Wanita", price: 160000, originalPrice: 210000, rating: 4.7, reviews: 205, description: "Rajutan tebal tapi adem, warna pastel aesthetic kekinian.", image: "https://picsum.photos/seed/cardigan126/400/300" },
            { id: 27, name: "Tunik Muslimah Katun Rayon", category: "Pakaian Wanita", price: 175000, originalPrice: 220000, rating: 4.8, reviews: 130, description: "Panjang menutup panggul, busui friendly dengan kancing depan.", image: "https://picsum.photos/seed/tunic127/400/300" },
            { id: 28, name: "Rok Plisket Pleated Maxi Skirt", category: "Pakaian Wanita", price: 110000, originalPrice: 150000, rating: 4.5, reviews: 275, description: "Lipatan konsisten rapi, pinggang karet melar nyaman dipakai.", image: "https://picsum.photos/seed/skirt128/400/300" },
            { id: 29, name: "Jaket Blazer Formal Office Wear", category: "Pakaian Wanita", price: 245000, originalPrice: 310000, rating: 4.7, reviews: 90, description: "Potongan profesional presisi, busa bahu tipis elegan.", image: "https://picsum.photos/seed/blazer129/400/300" },
            { id: 30, name: "Crop Top Casual Ribbed Cotton", category: "Pakaian Wanita", price: 75000, originalPrice: 100000, rating: 4.4, reviews: 180, description: "Bahan elastis pas di badan, simpel dan stylish untuk hang out.", image: "https://picsum.photos/seed/croptop130/400/300" },
            { id: 31, name: "Sweater Crewneck Minimalis Soft", category: "Pakaian Wanita", price: 155000, originalPrice: 195000, rating: 4.6, reviews: 115, description: "Potongan longgar nyaman, hangat dan ringan dipakai harian.", image: "https://picsum.photos/seed/w-sweater131/400/300" },
            { id: 32, name: "Piyama Pajamas Set Satin Silk", category: "Pakaian Wanita", price: 165000, originalPrice: 210000, rating: 4.9, reviews: 150, description: "Setelan baju tidur dingin berkilau halus, sangat nyaman untuk tidur.", image: "https://picsum.photos/seed/pajamas132/400/300" },

            // Sepatu & Aksesoris (10 items)
            { id: 33, name: "Sepatu Sneaker White Classic Unisex", category: "Sepatu & Aksesoris", price: 299000, originalPrice: 399000, rating: 4.8, reviews: 420, description: "Desain serbaguna clean white, insole empuk tidak membuat kaki pegal.", image: "https://picsum.photos/seed/sneaker133/400/300" },
            { id: 34, name: "Sepatu Lari Running Breathable Mesh", category: "Sepatu & Aksesoris", price: 345000, originalPrice: 450000, rating: 4.7, reviews: 260, description: "Sangat ringan, sol anti slip dengan peredam benturan optimal.", image: "https://picsum.photos/seed/runningshoes134/400/300" },
            { id: 35, name: "Tas Ransel Laptop Waterproof 15.6 Inch", category: "Sepatu & Aksesoris", price: 260000, originalPrice: 320000, rating: 4.8, reviews: 310, description: "Bahan tahan air, slot khusus laptop tebal aman dari benturan.", image: "https://picsum.photos/seed/backpack135/400/300" },
            { id: 36, name: "Dompet Kulit Asli Bifold Leather", category: "Sepatu & Aksesoris", price: 180000, originalPrice: 240000, rating: 4.9, reviews: 175, description: "Kulit sapi asli tahan lama, proteksi RFID blocker untuk kartu.", image: "https://picsum.photos/seed/wallet136/400/300" },
            { id: 37, name: "Kacamata Hitam Polarized Anti UV400", category: "Sepatu & Aksesoris", price: 120000, originalPrice: 180000, rating: 4.6, reviews: 215, description: "Lensa polarized redupkan silau matahari, bingkai kokoh ringan.", image: "https://picsum.photos/seed/sunglasses137/400/300" },
            { id: 38, name: "Jam Tangan Pria Chronograph Analog", category: "Sepatu & Aksesoris", price: 499000, originalPrice: 650000, rating: 4.7, reviews: 95, description: "Tali rantai stainless steel, fitur tanggal & stopwatch aktif.", image: "https://picsum.photos/seed/m-watch138/400/300" },
            { id: 39, name: "Sandal Slide Casual Rubber Anti-Slippery", category: "Sepatu & Aksesoris", price: 85000, originalPrice: 120000, rating: 4.5, reviews: 380, description: "Bahan empuk empuk seperti awan, tahan air dan tidak licin.", image: "https://picsum.photos/seed/sandals139/400/300" },
            { id: 40, name: "Tas Selempang Slingbag Minimalis", category: "Sepatu & Aksesoris", price: 135000, originalPrice: 175000, rating: 4.6, reviews: 140, description: "Kapasitas pas untuk HP, dompet, kunci & kebutuhan harian.", image: "https://picsum.photos/seed/slingbag140/400/300" },

            // Rumah Tangga & Olahraga (10 items)
            { id: 41, name: "Air Fryer Low Watt 3.5 Liter", category: "Rumah Tangga", price: 685000, originalPrice: 850000, rating: 4.9, reviews: 230, description: "Goreng makanan tanpa minyak sehat, wadah anti lengket mudah dicuci.", image: "https://picsum.photos/seed/airfryer141/400/300" },
            { id: 42, name: "Blender Juicer Portable USB 6 Pisau", category: "Rumah Tangga", price: 145000, originalPrice: 195000, rating: 4.4, reviews: 290, description: "Bisa diisi ulang via USB, haluskan buah es batu cepat di mana saja.", image: "https://picsum.photos/seed/blender142/400/300" },
            { id: 43, name: "Humidifier Diffuser Aroma 500ml", category: "Rumah Tangga", price: 160000, originalPrice: 220000, rating: 4.7, reviews: 185, description: "Disertai remote control & lampu LED 7 warna, harumkan ruangan.", image: "https://picsum.photos/seed/humidifier143/400/300" },
            { id: 44, name: "Lampu Meja Belajar LED Eye-Care", category: "Rumah Tangga", price: 115000, originalPrice: 150000, rating: 4.6, reviews: 140, description: "Tingkat kecerahan adjustable touch sensor, tidak bikin mata lelah.", image: "https://picsum.photos/seed/deskled144/400/300" },
            { id: 45, name: "Timbangan Badan Digital Presisi", category: "Rumah Tangga", price: 95000, originalPrice: 130000, rating: 4.8, reviews: 210, description: "Kaca tempered tebal, indikator suhu & kapasitas beban hingga 180kg.", image: "https://picsum.photos/seed/scale145/400/300" },
            { id: 46, name: "Matras Yoga Anti Selip TPE 6mm", category: "Olahraga", price: 175000, originalPrice: 230000, rating: 4.8, reviews: 160, description: "Bahan ramah lingkungan ramah sendi, tidak licin walau berkeringat.", image: "https://picsum.photos/seed/yogamat146/400/300" },
            { id: 47, name: "Dumbbell Set Adjust Karet 10kg", category: "Olahraga", price: 295000, originalPrice: 380000, rating: 4.7, reviews: 105, description: "Plat beban bisa disesuaikan, gagang ergonomis mantap digenggam.", image: "https://picsum.photos/seed/dumbbell147/400/300" },
            { id: 48, name: "Botol Minum Motivasi 2 Liter BPA Free", category: "Olahraga", price: 65000, originalPrice: 90000, rating: 4.5, reviews: 620, description: "Disertai penanda waktu hidrasi, sedotan silicone anti bocor.", image: "https://picsum.photos/seed/waterbottle148/400/300" },
            { id: 49, name: "Resistance Loop Band Gym Set 5 In 1", category: "Olahraga", price: 78000, originalPrice: 110000, rating: 4.6, reviews: 135, description: "Tingkat resistensi berbeda untuk latihan otot seluruh tubuh.", image: "https://picsum.photos/seed/resistanceband149/400/300" },
            { id: 50, name: "Tenda Camping Dome 4 Orang Double Layer", category: "Olahraga", price: 540000, originalPrice: 690000, rating: 4.9, reviews: 75, description: "Bahan tahan hujan air deras, frame fiberglass kokoh mudah dirakit.", image: "https://picsum.photos/seed/tent150/400/300" }
        ];

        // Application State
        let state = {
            products: [...rawProducts],
            filteredProducts: [...rawProducts],
            cart: [],
            categories: ["Semua", "Elektronik", "Pakaian Pria", "Pakaian Wanita", "Sepatu & Aksesoris", "Rumah Tangga", "Olahraga"],
            filters: {
                search: "",
                category: "Semua",
                maxPrice: 15000000,
                minRating: 0,
                sortBy: "default"
            },
            pagination: {
                currentPage: 1,
                itemsPerPage: 12
            },
            viewMode: "grid" // 'grid' or 'list'
        };

        // Utility: Format Currency to Indonesian Rupiah
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Initialize App
        document.addEventListener('DOMContentLoaded', () => {
            renderCategoryFilters();
            applyFilters();
            setupEventListeners();
        });

        function renderCategoryFilters() {
            const container = document.getElementById('categoryContainer');
            container.innerHTML = '';

            state.categories.forEach(cat => {
                const count = cat === 'Semua' 
                    ? rawProducts.length 
                    : rawProducts.filter(p => p.category === cat).length;

                const isChecked = state.filters.category === cat ? 'checked' : '';
                
                const div = document.createElement('div');
                div.className = 'form-check d-flex justify-content-between align-items-center';
                div.innerHTML = `
                    <div>
                        <input class="form-check-input category-radio" type="radio" name="categoryRadio" id="cat_${cat}" value="${cat}" ${isChecked}>
                        <label class="form-check-label small" for="cat_${cat}">
                            ${cat}
                        </label>
                    </div>
                    <span class="badge bg-light text-secondary rounded-pill small">${count}</span>
                `;
                container.appendChild(div);
            });
        }

        function applyFilters() {
            let result = [...state.products];

            // 1. Search Filter
            if (state.filters.search.trim() !== "") {
                const q = state.filters.search.toLowerCase().trim();
                result = result.filter(p => 
                    p.name.toLowerCase().includes(q) || 
                    p.description.toLowerCase().includes(q) ||
                    p.category.toLowerCase().includes(q)
                );
            }

            // 2. Category Filter
            if (state.filters.category !== "Semua") {
                result = result.filter(p => p.category === state.filters.category);
            }

            // 3. Price Filter
            result = result.filter(p => p.price <= state.filters.maxPrice);

            // 4. Rating Filter
            if (state.filters.minRating > 0) {
                result = result.filter(p => p.rating >= state.filters.minRating);
            }

            // 5. Sorting
            switch (state.filters.sortBy) {
                case "price-asc":
                    result.sort((a, b) => a.price - b.price);
                    break;
                case "price-desc":
                    result.sort((a, b) => b.price - a.price);
                    break;
                case "name-asc":
                    result.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case "name-desc":
                    result.sort((a, b) => b.name.localeCompare(a.name));
                    break;
                case "rating-desc":
                    result.sort((a, b) => b.rating - a.rating);
                    break;
                default:
                    // default order
                    break;
            }

            state.filteredProducts = result;
            state.pagination.currentPage = 1; // Reset to page 1 on filter change
            renderProducts();
            renderPagination();
            updateCounts();
        }

        function renderProducts() {
            const container = document.getElementById('productGrid');
            const emptyState = document.getElementById('emptyState');
            container.innerHTML = '';

            if (state.filteredProducts.length === 0) {
                emptyState.classList.remove('d-none');
                return;
            } else {
                emptyState.classList.add('d-none');
            }

            // Calculate pagination bounds
            const start = (state.pagination.currentPage - 1) * state.pagination.itemsPerPage;
            const end = start + state.pagination.itemsPerPage;
            const pageProducts = state.filteredProducts.slice(start, end);

            pageProducts.forEach(product => {
                const col = document.createElement('div');
                
                if (state.viewMode === 'grid') {
                    col.className = 'col-sm-6 col-md-4 col-xl-4 d-flex align-items-stretch';
                    col.innerHTML = `
                        <div class="card product-card w-100">
                            <div class="product-img-wrapper">
                                <span class="badge-category">${product.category}</span>
                                <button class="btn-wishlist" onclick="toggleWishlist(this, ${product.id})" title="Tambah ke Wishlist">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <img src="${product.image}" alt="${product.name}" loading="lazy" onerror="this.src='https://placehold.co/400x300/e2e8f0/64748b?text=Gambar+Produk'">
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <div class="mb-1 d-flex align-items-center gap-1">
                                    <span class="rating-stars"><i class="bi bi-star-fill"></i> ${product.rating}</span>
                                    <span class="text-muted small">(${product.reviews})</span>
                                </div>
                                <h6 class="card-title fw-bold text-dark text-truncate mb-1" title="${product.name}">${product.name}</h6>
                                <p class="card-text text-muted small mb-2 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    ${product.description}
                                </p>
                                <div class="mb-3">
                                    <div class="price-text">${formatRupiah(product.price)}</div>
                                    <div class="original-price">${formatRupiah(product.originalPrice)}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm flex-grow-1" onclick="openDetailModal(${product.id})">
                                        Detail
                                    </button>
                                    <button class="btn btn-primary btn-sm px-3" onclick="addToCart(${product.id})" title="Tambah ke keranjang">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // List View
                    col.className = 'col-12';
                    col.innerHTML = `
                        <div class="card product-card list-view w-100 p-2">
                            <div class="product-img-wrapper rounded-2">
                                <span class="badge-category">${product.category}</span>
                                <img src="${product.image}" alt="${product.name}" loading="lazy" onerror="this.src='https://placehold.co/400x300/e2e8f0/64748b?text=Gambar+Produk'">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="fw-bold text-dark mb-1">${product.name}</h5>
                                        <div class="rating-stars small"><i class="bi bi-star-fill"></i> ${product.rating} (${product.reviews})</div>
                                    </div>
                                    <p class="text-muted small mb-2">${product.description}</p>
                                </div>
                                <div class="d-flex align-items-end justify-content-between flex-wrap gap-2">
                                    <div>
                                        <div class="price-text">${formatRupiah(product.price)}</div>
                                        <div class="original-price">${formatRupiah(product.originalPrice)}</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-primary btn-sm" onclick="openDetailModal(${product.id})">Detail Produk</button>
                                        <button class="btn btn-primary btn-sm" onclick="addToCart(${product.id})"><i class="bi bi-cart-plus me-1"></i> Beli Sekarang</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                container.appendChild(col);
            });
        }

        function renderPagination() {
            const paginationUl = document.getElementById('pagination');
            paginationUl.innerHTML = '';

            const totalPages = Math.ceil(state.filteredProducts.length / state.pagination.itemsPerPage);
            if (totalPages <= 1) return;

            // Previous Button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${state.pagination.currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${state.pagination.currentPage - 1}); return false;">&laquo;</a>`;
            paginationUl.appendChild(prevLi);

            // Page Number Links
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${state.pagination.currentPage === i ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>`;
                paginationUl.appendChild(li);
            }

            // Next Button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${state.pagination.currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${state.pagination.currentPage + 1}); return false;">&raquo;</a>`;
            paginationUl.appendChild(nextLi);
        }

        function changePage(page) {
            state.pagination.currentPage = page;
            renderProducts();
            renderPagination();
            // Scroll back to top of section
            document.getElementById('productSection').scrollIntoView({ behavior: 'smooth' });
        }

        function updateCounts() {
            const total = state.filteredProducts.length;
            const showing = Math.min(total, state.pagination.itemsPerPage);
            document.getElementById('showingCount').textContent = total === 0 ? 0 : showing;
            document.getElementById('totalCount').textContent = total;
        }

        function addToCart(productId) {
            const product = rawProducts.find(p => p.id === productId);
            if (!product) return;

            const existingItem = state.cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                state.cart.push({ ...product, qty: 1 });
            }

            updateCartUI();
            showToast(`"${product.name}" telah ditambahkan ke keranjang!`);
        }

        function updateCartQuantity(productId, delta) {
            const item = state.cart.find(i => i.id === productId);
            if (!item) return;

            item.qty += delta;
            if (item.qty <= 0) {
                state.cart = state.cart.filter(i => i.id !== productId);
            }

            updateCartUI();
        }

        function updateCartUI() {
            const cartCountBadge = document.getElementById('cartCount');
            const cartContainer = document.getElementById('cartItemsContainer');
            const cartTotalPrice = document.getElementById('cartTotalPrice');

            const totalQty = state.cart.reduce((acc, item) => acc + item.qty, 0);
            const totalPrice = state.cart.reduce((acc, item) => acc + (item.price * item.qty), 0);

            cartCountBadge.textContent = totalQty;
            cartTotalPrice.textContent = formatRupiah(totalPrice);

            if (state.cart.length === 0) {
                cartContainer.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-3 text-muted opacity-50"></i>
                        <p class="mt-3 text-muted">Keranjang belanja Anda masih kosong.</p>
                    </div>
                `;
                return;
            }

            cartContainer.innerHTML = '';
            state.cart.forEach(item => {
                const itemEl = document.createElement('div');
                itemEl.className = 'd-flex align-items-center gap-3 mb-3 p-2 border-bottom';
                itemEl.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="mb-0 text-truncate font-weight-bold" style="max-width: 160px;">${item.name}</h6>
                        <small class="text-primary fw-bold">${formatRupiah(item.price)}</small>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="updateCartQuantity(${item.id}, -1)">-</button>
                        <span class="px-2 small fw-bold">${item.qty}</span>
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="updateCartQuantity(${item.id}, 1)">+</button>
                    </div>
                `;
                cartContainer.appendChild(itemEl);
            });
        }

        function handleCheckout() {
            if (state.cart.length === 0) {
                alert("Keranjang belanja Anda kosong!");
                return;
            }
            alert(`Terima kasih telah berbelanja! Total pembayaran: ${document.getElementById('cartTotalPrice').textContent}`);
            state.cart = [];
            updateCartUI();
            const offcanvasEl = document.getElementById('cartOffcanvas');
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
            if (bsOffcanvas) bsOffcanvas.hide();
        }

        function openDetailModal(productId) {
            const product = rawProducts.find(p => p.id === productId);
            if (!product) return;

            const modalContent = document.getElementById('modalDetailContent');
            modalContent.innerHTML = `
                <div class="col-md-6">
                    <img src="${product.image}" class="img-fluid rounded-3 w-100 shadow-sm" alt="${product.name}">
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-primary-subtle text-primary mb-2">${product.category}</span>
                        <h4 class="fw-bold text-dark mb-2">${product.name}</h4>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="rating-stars"><i class="bi bi-star-fill"></i> ${product.rating}</span>
                            <span class="text-muted small">| ${product.reviews} ulasan pembeli</span>
                        </div>
                        <h3 class="price-text mb-1">${formatRupiah(product.price)}</h3>
                        <p class="original-price mb-3">${formatRupiah(product.originalPrice)}</p>
                        <hr>
                        <p class="text-secondary small mb-4">${product.description}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1 py-2 fw-semibold" onclick="addToCart(${product.id})">
                            <i class="bi bi-cart-plus me-1"></i> Tambah Ke Keranjang
                        </button>
                    </div>
                </div>
            `;

            const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
            modal.show();
        }

        function toggleWishlist(btn, id) {
            const icon = btn.querySelector('i');
            if (icon.classList.contains('bi-heart')) {
                icon.classList.replace('bi-heart', 'bi-heart-fill');
                showToast("Berhasil ditambahkan ke daftar keinginan!");
            } else {
                icon.classList.replace('bi-heart-fill', 'bi-heart');
                showToast("Dihapus dari daftar keinginan.");
            }
        }

        function showToast(message) {
            const toastEl = document.getElementById('cartToast');
            document.getElementById('toastMessage').textContent = message;
            const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
            toast.show();
        }

        function resetFilters() {
            state.filters = {
                search: "",
                category: "Semua",
                maxPrice: 15000000,
                minRating: 0,
                sortBy: "default"
            };

            document.getElementById('filterSearch').value = "";
            document.getElementById('navbarSearch').value = "";
            document.getElementById('priceRange').value = 15000000;
            document.getElementById('priceRangeValue').textContent = "Rp 15.000.000";
            document.getElementById('ratingFilter').value = "0";
            document.getElementById('sortBy').value = "default";

            renderCategoryFilters();
            applyFilters();
        }

        function setupEventListeners() {
            // Search Event (Sidebar & Navbar)
            const handleSearch = (val) => {
                state.filters.search = val;
                applyFilters();
            };

            document.getElementById('filterSearch').addEventListener('input', (e) => handleSearch(e.target.value));
            document.getElementById('navbarSearch').addEventListener('input', (e) => {
                document.getElementById('filterSearch').value = e.target.value;
                handleSearch(e.target.value);
            });
            document.getElementById('btnNavbarSearch').addEventListener('click', () => {
                const val = document.getElementById('navbarSearch').value;
                handleSearch(val);
            });

            // Category Change Event (Delegation)
            document.getElementById('categoryContainer').addEventListener('change', (e) => {
                if (e.target.classList.contains('category-radio')) {
                    state.filters.category = e.target.value;
                    applyFilters();
                }
            });

            // Price Range Event
            const priceRange = document.getElementById('priceRange');
            const priceVal = document.getElementById('priceRangeValue');
            priceRange.addEventListener('input', (e) => {
                const val = parseInt(e.target.value);
                priceVal.textContent = formatRupiah(val);
                state.filters.maxPrice = val;
                applyFilters();
            });

            // Rating Filter
            document.getElementById('ratingFilter').addEventListener('change', (e) => {
                state.filters.minRating = parseFloat(e.target.value);
                applyFilters();
            });

            // Sort Dropdown
            document.getElementById('sortBy').addEventListener('change', (e) => {
                state.filters.sortBy = e.target.value;
                applyFilters();
            });

            // Reset Button
            document.getElementById('btnResetFilter').addEventListener('click', resetFilters);

            // View Switcher Buttons
            document.getElementById('btnGridView').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('btnListView').classList.remove('active');
                state.viewMode = 'grid';
                renderProducts();
            });

            document.getElementById('btnListView').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('btnGridView').classList.remove('active');
                state.viewMode = 'list';
                renderProducts();
            });
        }