<?php
session_start();
require_once 'koneksi_db.php';

// Ensure the cart session variable exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Redirect back to the cart if there is nothing to check out
if (empty($_SESSION['cart'])) {
    header('Location: keranjang.php');
    exit;
}

// Load the products that are in the cart
$items = [];
$total = 0;
$total_items = 0;

$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT id, name, price, category, image FROM products WHERE id IN ($placeholders)");
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

// Validation errors and previously entered values (from process_order.php)
$errors = isset($_SESSION['checkout_errors']) ? $_SESSION['checkout_errors'] : [];
$old = isset($_SESSION['checkout_old']) ? $_SESSION['checkout_old'] : ['user_name' => '', 'address' => '', 'phone' => ''];
unset($_SESSION['checkout_errors'], $_SESSION['checkout_old']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
        .checkout-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 25px;
        }
        .cart-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            background: #f5f5f5;
        }
        .no-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 18px;
        }
        .btn-primary-custom {
            background: #667eea;
            border: none;
            border-radius: 25px;
            padding: 12px 24px;
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
            padding: 10px 22px;
            color: #667eea;
            font-weight: 600;
            background: transparent;
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
            <h1>💳 Checkout</h1>
            <p class="mb-0">Tinjau pesanan Anda dan lengkapi data pengiriman</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container-lg">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Mohon periksa kembali data Anda:</strong>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="process_order.php" method="post">
            <div class="row g-4">
                <!-- Review Order Items -->
                <div class="col-lg-7">
                    <div class="checkout-card mb-4">
                        <h4 class="mb-3">Review Pesanan</h4>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
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
                                                        <div class="fw-semibold"><?php echo htmlspecialchars($product['name']); ?></div>
                                                        <?php if (!empty($product['category'])): ?>
                                                            <div class="small text-muted"><?php echo htmlspecialchars($product['category']); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                                            <td class="text-end fw-bold">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h4 class="mb-3">Data Pengiriman</h4>
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_name" name="user_name"
                                   value="<?php echo htmlspecialchars($old['user_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($old['address']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="<?php echo htmlspecialchars($old['phone']); ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="col-lg-5">
                    <div class="summary-card">
                        <h4 class="mb-3">Ringkasan Pembayaran</h4>
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
                        <button type="submit" class="btn btn-primary-custom w-100">Buat Pesanan</button>
                        <a href="keranjang.php" class="btn btn-outline-custom w-100 mt-2">← Kembali ke Keranjang</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
