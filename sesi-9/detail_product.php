<?php
session_start();
require_once 'koneksi_db.php';

// Get the product id from the query string
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Read the product data using a prepared statement
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$product = $stmt->fetch();

// Redirect back to the catalog if the product does not exist
if (!$product) {
    header('Location: index.php');
    exit;
}

// Fetch related products from the same category
$related = [];
if (!empty($product['category'])) {
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE category = ? AND id != ? ORDER BY id DESC LIMIT 4");
    $stmt->execute([$product['category'], $product['id']]);
    $related = $stmt->fetchAll();
}

// Total number of items in the cart (for the navbar badge)
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

$has_stock = array_key_exists('stock', $product);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Etalase Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
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
        .detail-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }
        .detail-image-container {
            width: 100%;
            height: 400px;
            background: #f5f5f5;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .detail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }
        .product-category {
            display: inline-block;
            font-size: 12px;
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            padding: 6px 14px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        .product-name {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        .product-price {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
        }
        .product-description {
            font-size: 15px;
            color: #666;
            line-height: 1.7;
            margin-bottom: 25px;
        }
        .btn-primary-custom {
            background: #667eea;
            border: none;
            border-radius: 25px;
            padding: 12px 28px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background: #5568d3;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-outline-custom {
            border: 2px solid #667eea;
            border-radius: 25px;
            padding: 10px 26px;
            color: #667eea;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
        }
        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin: 50px 0 25px;
        }
        .related-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .related-image-container {
            width: 100%;
            height: 180px;
            background: #f5f5f5;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .related-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Etalase Produk</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="keranjang.php">
                            Keranjang
                            <?php if ($cart_count > 0): ?>
                                <span class="badge rounded-pill text-bg-primary"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="page-header">
        <div class="container-lg">
            <h1>📦 Detail Produk</h1>
            <p class="mb-0">Informasi lengkap tentang produk pilihan Anda</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container-lg">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" style="color: #667eea; text-decoration: none;">Home</a></li>
                <li class="breadcrumb-item"><a href="index.php?category=<?php echo urlencode($product['category']); ?>" style="color: #667eea; text-decoration: none;"><?php echo htmlspecialchars($product['category']); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>

        <div class="detail-card">
            <div class="row g-4">
                <!-- Product Image -->
                <div class="col-lg-6">
                    <div class="detail-image-container">
                        <?php if (!empty($product['image']) && file_exists('uploads/' . basename($product['image']))): ?>
                            <img src="uploads/<?php echo htmlspecialchars(basename($product['image'])); ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 class="detail-image">
                        <?php else: ?>
                            <div class="no-image">
                                📷 Tidak ada gambar
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <?php if (!empty($product['category'])): ?>
                        <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                    <?php endif; ?>

                    <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h1>

                    <div class="product-price">
                        Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                    </div>

                    <?php if ($has_stock): ?>
                        <p class="mb-3">
                            <?php if ((int)$product['stock'] > 0): ?>
                                <span class="badge text-bg-success">Stok tersedia: <?php echo (int)$product['stock']; ?></span>
                            <?php else: ?>
                                <span class="badge text-bg-danger">Stok habis</span>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($product['description'])): ?>
                        <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <?php endif; ?>

                    <!-- Add to Cart -->
                    <form action="add_to_cart.php" method="post" class="d-flex flex-wrap gap-2 align-items-center mb-3">
                        <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width: 110px;" aria-label="Jumlah">
                        <button type="submit" class="btn btn-primary-custom">
                            🛒 Tambah ke Keranjang
                        </button>
                    </form>

                    <a href="index.php" class="btn btn-outline-custom">← Kembali ke Etalase</a>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related)): ?>
            <h2 class="section-title">Produk Terkait</h2>
            <div class="row g-4">
                <?php foreach ($related as $item): ?>
                    <div class="col-6 col-md-3">
                        <a href="detail_product.php?id=<?php echo (int)$item['id']; ?>" class="text-decoration-none">
                            <div class="related-card">
                                <div class="related-image-container">
                                    <?php if (!empty($item['image']) && file_exists('uploads/' . basename($item['image']))): ?>
                                        <img src="uploads/<?php echo htmlspecialchars(basename($item['image'])); ?>"
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             class="related-image">
                                    <?php else: ?>
                                        <div class="no-image">📷</div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-3">
                                    <div class="product-name" style="font-size: 16px; margin-bottom: 8px;">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </div>
                                    <div class="fw-bold" style="color: #667eea;">
                                        Rp <?php echo number_format($item['price'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>