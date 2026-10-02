<?php
session_start();
require_once 'koneksi_db.php';

// Ensure the cart session variable exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions (update quantity, remove item, clear cart)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($_POST['action'] === 'remove' && $id > 0) {
        unset($_SESSION['cart'][$id]);
    } elseif ($_POST['action'] === 'update' && $id > 0) {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantity > 0) {
            $_SESSION['cart'][$id] = $quantity;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    } elseif ($_POST['action'] === 'clear') {
        $_SESSION['cart'] = [];
    }

    header('Location: keranjang.php');
    exit;
}

// Load the products that are in the cart
$items = [];
$total = 0;
$total_items = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT id, name, price, description, category, image FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        $quantity = (int)$_SESSION['cart'][$product['id']];
        $subtotal = $product['price'] * $quantity;

        $total += $subtotal;
        $total_items += $quantity;

        $items[] = [
            'product'  => $product,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
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
        .cart-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 25px;
        }
        .cart-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            background: #f5f5f5;
        }
        .no-image {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 20px;
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
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-outline-custom {
            border: 2px solid #667eea;
            border-radius: 25px;
            padding: 8px 22px;
            color: #667eea;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
        }
        .summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 25px;
            position: sticky;
            top: 20px;
        }
        .summary-total {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
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
        .quantity-input {
            max-width: 90px;
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
                        <a class="nav-link active" aria-current="page" href="keranjang.php">
                            Keranjang
                            <?php if ($total_items > 0): ?>
                                <span class="badge rounded-pill text-bg-primary"><?php echo $total_items; ?></span>
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
            <h1>🛒 Keranjang Belanja</h1>
            <p class="mb-0">
                <?php if ($total_items > 0): ?>
                    <?php echo $total_items; ?> item siap untuk dipesan
                <?php else: ?>
                    Keranjang Anda masih kosong
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container-lg">
        <?php if (!empty($items)): ?>
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-card">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th style="width: 140px;">Jumlah</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <?php $product = $item['product']; ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php if (!empty($product['image']) && file_exists('uploads/' . basename($product['image']))): ?>
                                                        <img src="uploads/<?php echo htmlspecialchars(basename($product['image'])); ?>"
                                                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                             class="cart-thumb">
                                                    <?php else: ?>
                                                        <div class="no-image">📷</div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <a href="detail_product.php?id=<?php echo (int)$product['id']; ?>" class="fw-semibold text-decoration-none" style="color: #333;">
                                                            <?php echo htmlspecialchars($product['name']); ?>
                                                        </a>
                                                        <?php if (!empty($product['category'])): ?>
                                                            <div class="small text-muted"><?php echo htmlspecialchars($product['category']); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                            <td>
                                                <form action="keranjang.php" method="post" class="d-flex gap-2">
                                                    <input type="hidden" name="action" value="update">
                                                    <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
                                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0" class="form-control form-control-sm quantity-input">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Perbarui jumlah">↻</button>
                                                </form>
                                            </td>
                                            <td class="fw-bold">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                            <td>
                                                <form action="keranjang.php" method="post">
                                                    <input type="hidden" name="action" value="remove">
                                                    <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk ini dari keranjang?')" title="Hapus">✕</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="index.php" class="btn btn-outline-custom">← Lanjut Belanja</a>
                        <form action="keranjang.php" method="post">
                            <input type="hidden" name="action" value="clear">
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Kosongkan seluruh keranjang?')">Kosongkan Keranjang</button>
                        </form>
                    </div>
                </div>

                <!-- Summary -->
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h4 class="mb-3">Ringkasan Belanja</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Jumlah item</span>
                            <span><?php echo $total_items; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-semibold">Total</span>
                            <span class="summary-total">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-primary-custom w-100">Checkout</a>
                        <p class="text-muted small text-center mt-2 mb-0">Lanjutkan untuk mengisi data pengiriman</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-card">
                <div class="empty-state">
                    <div class="empty-state-icon">🛒</div>
                    <h3>Keranjang Masih Kosong</h3>
                    <p>Belum ada produk yang ditambahkan ke keranjang Anda</p>
                    <a href="index.php" class="btn btn-primary-custom" style="margin-top: 20px;">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
