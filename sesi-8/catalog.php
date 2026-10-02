<?php
require_once 'koneksi_db.php';

// Get all categories for filter
$categories_sql = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category";
$categories = $pdo->query($categories_sql)->fetchAll();

// Get search and filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Build SQL query with filters
$sql = "SELECT id, name, price, description, category, image FROM products WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND name LIKE ?";
    $params[] = '%' . $search . '%';
}

if (!empty($category_filter)) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
}

$sql .= " ORDER BY id DESC";

// Execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etalase Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .page-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .filter-section h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            padding-left: 40px;
            border-radius: 25px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        .btn-clear {
            background: #f0f0f0;
            color: #333;
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        .btn-clear:hover {
            background: #e0e0e0;
            color: #333;
        }
        .category-badge {
            display: inline-block;
            padding: 8px 15px;
            margin: 5px;
            background: white;
            border: 2px solid #ddd;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }
        .category-badge:hover {
            border-color: #667eea;
            color: #667eea;
        }
        .category-badge.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .product-image-container {
            width: 100%;
            height: 250px;
            background: #f5f5f5;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
        .no-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 14px;
        }
        .product-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .product-category {
            font-size: 12px;
            color: #667eea;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .product-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.3;
            flex-grow: 1;
        }
        .product-description {
            font-size: 13px;
            color: #666;
            margin-bottom: 12px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-top: auto;
        }
        .product-price-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        .empty-state h3 {
            color: #666;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .result-count {
            color: #666;
            font-size: 14px;
            margin-top: 15px;
        }
        .btn-primary-custom {
            background: #667eea;
            border: none;
            border-radius: 25px;
            padding: 10px 24px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }
            .filter-section {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="page-header">
        <div class="container-lg">
            <h1>🛍️ Etalase Produk</h1>
            <p class="mb-0">Temukan produk terbaik dengan harga terjangkau</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container-lg">
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row align-items-end">
                <!-- Search Box -->
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <h5>Cari Produk</h5>
                    <form method="GET" class="search-box">
                        <span class="search-icon">🔍</span>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Ketik nama produk..." 
                            value="<?php echo htmlspecialchars($search); ?>"
                        >
                        <?php if (!empty($category_filter)): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_filter); ?>">
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Clear Filters Button -->
                <div class="col-lg-6 text-lg-end">
                    <?php if (!empty($search) || !empty($category_filter)): ?>
                        <a href="catalog.php" class="btn btn-clear">
                            ✕ Hapus Filter
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Category Filter -->
            <div style="margin-top: 20px;">
                <h5 style="margin-bottom: 12px;">Kategori</h5>
                <div>
                    <a href="catalog.php<?php echo !empty($search) ? '?search=' . urlencode($search) : ''; ?>" 
                       class="category-badge <?php echo empty($category_filter) ? 'active' : ''; ?>">
                        Semua Kategori
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="catalog.php?category=<?php echo urlencode($cat['category']); ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                           class="category-badge <?php echo $category_filter === $cat['category'] ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($cat['category']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Result Summary -->
        <div class="result-count">
            <strong><?php echo count($products); ?></strong> produk ditemukan
            <?php if (!empty($search)): ?>
                untuk "<strong><?php echo htmlspecialchars($search); ?></strong>"
            <?php endif; ?>
            <?php if (!empty($category_filter)): ?>
                di kategori "<strong><?php echo htmlspecialchars($category_filter); ?></strong>"
            <?php endif; ?>
        </div>

        <!-- Products Grid -->
        <?php if (count($products) > 0): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <!-- Product Image -->
                        <div class="product-image-container">
                            <?php if (!empty($product['image']) && file_exists('uploads/' . basename($product['image']))): ?>
                                <img src="uploads/<?php echo htmlspecialchars(basename($product['image'])); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-image">
                            <?php else: ?>
                                <div class="no-image">
                                    📷 Tidak ada gambar
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Info -->
                        <div class="product-info">
                            <?php if (!empty($product['category'])): ?>
                                <div class="product-category">
                                    <?php echo htmlspecialchars($product['category']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h6 class="product-name">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h6>

                            <?php if (!empty($product['description'])): ?>
                                <p class="product-description">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </p>
                            <?php endif; ?>

                            <div class="product-price-label">Harga</div>
                            <div class="product-price">
                                Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>Produk Tidak Ditemukan</h3>
                <p>Coba ubah kriteria pencarian atau filter kategori Anda</p>
                <a href="catalog.php" class="btn btn-primary-custom" style="margin-top: 20px;">
                    Lihat Semua Produk
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer Navigation -->
    <div style="text-align: center; margin-top: 40px; padding: 20px; border-top: 1px solid #e0e0e0; color: #999; font-size: 14px;">
        <a href="home.php" style="color: #667eea; text-decoration: none; margin-right: 20px;">← Kembali ke Menu</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-submit search on enter
        document.querySelectorAll('input[name="search"]').forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.form.submit();
                }
            });
        });
    </script>
</body>
</html>
