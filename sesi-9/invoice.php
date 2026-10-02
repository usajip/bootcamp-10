<?php
session_start();
require_once 'koneksi_db.php';

// Get the order id from the query string
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Read the order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

// Redirect back to the catalog when the order does not exist
if (!$order) {
    header('Location: index.php');
    exit;
}

// Read the order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ? ORDER BY id ASC");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

// Status badge styling
$status = strtolower($order['status']);
$statusClass = 'text-bg-secondary';
if ($status === 'pending') {
    $statusClass = 'text-bg-warning';
} elseif ($status === 'paid' || $status === 'completed') {
    $statusClass = 'text-bg-success';
} elseif ($status === 'cancelled') {
    $statusClass = 'text-bg-danger';
}

// Total number of items in the cart (for the navbar badge)
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo htmlspecialchars($order['id']); ?></title>
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
        .invoice-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 35px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
        }
        .invoice-meta dt {
            color: #999;
            font-weight: 500;
        }
        .invoice-meta dd {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .invoice-total {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
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
            background: transparent;
            transition: all 0.3s;
        }
        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
            }
            .invoice-card {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary no-print">
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
    <div class="page-header no-print">
        <div class="container-lg">
            <h1>🧾 Invoice</h1>
            <p class="mb-0">Terima kasih, pesanan Anda telah kami terima</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container-lg">
        <div class="invoice-card">
            <!-- Invoice Header -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="invoice-title">INVOICE</div>
                    <div class="text-muted">#<?php echo htmlspecialchars($order['id']); ?></div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="mb-1">
                        <span class="badge <?php echo $statusClass; ?> text-uppercase"><?php echo htmlspecialchars($order['status']); ?></span>
                    </div>
                    <div class="text-muted small">
                        Tanggal: <?php echo date('d M Y H:i', strtotime($order['date_time'])); ?>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Customer Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted text-uppercase mb-2">Ditagihkan Kepada</h6>
                    <dl class="invoice-meta mb-0">
                        <dd><?php echo htmlspecialchars($order['user_name']); ?></dd>
                        <dd class="fw-normal"><?php echo nl2br(htmlspecialchars($order['address'])); ?></dd>
                        <dd class="fw-normal">📞 <?php echo htmlspecialchars($order['phone']); ?></dd>
                    </dl>
                </div>
            </div>

            <!-- Order Items -->
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td class="text-center"><?php echo (int)$item['quantity']; ?></td>
                                <td class="text-end">Rp <?php echo number_format($item['total_price'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end fw-semibold">Total</td>
                            <td class="text-end invoice-total">Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between mt-4 no-print">
                <a href="index.php" class="btn btn-outline-custom">← Kembali ke Etalase</a>
                <!-- Whatsapp Button to confirm order -->
                 <a href="https://api.whatsapp.com/send?phone=6281234567890&text=Konfirmasi%20pesanan%20dengan%20invoice%20ID%20<?php echo urlencode($order['id']); ?>" class="btn btn-success" target="_blank">💬 Konfirmasi via WhatsApp</a>
                <button type="button" class="btn btn-primary-custom" onclick="window.print()">🖨️ Cetak Invoice</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
