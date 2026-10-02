<?php
session_start();
require_once 'koneksi_db.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: keranjang.php');
    exit;
}

// Collect and trim the submitted data
$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$address   = isset($_POST['address']) ? trim($_POST['address']) : '';
$phone     = isset($_POST['phone']) ? trim($_POST['phone']) : '';

$cart = (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) ? $_SESSION['cart'] : [];

// Validate the input
$errors = [];
if ($user_name === '') {
    $errors[] = 'Nama penerima harus diisi.';
}
if ($address === '') {
    $errors[] = 'Alamat harus diisi.';
}
if ($phone === '') {
    $errors[] = 'Nomor telepon harus diisi.';
}
if (empty($cart)) {
    $errors[] = 'Keranjang Anda masih kosong.';
}

// Send the user back to the checkout page when validation fails
if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    $_SESSION['checkout_old'] = [
        'user_name' => $user_name,
        'address'   => $address,
        'phone'     => $phone,
    ];
    header('Location: checkout.php');
    exit;
}

try {
    // Recalculate prices from the database (never trust client-side values)
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    // Map products by id for quick lookup
    $productMap = [];
    foreach ($products as $product) {
        $productMap[(int)$product['id']] = $product;
    }

    // Build the order item list and compute the total
    $orderItems = [];
    $total = 0;

    foreach ($cart as $productId => $quantity) {
        $productId = (int)$productId;
        $quantity  = (int)$quantity;

        if ($quantity < 1 || !isset($productMap[$productId])) {
            continue;
        }

        $product   = $productMap[$productId];
        $lineTotal = $product['price'] * $quantity;

        $total += $lineTotal;

        $orderItems[] = [
            'product_id'   => $productId,
            'product_name' => $product['name'],
            'quantity'     => $quantity,
            'total_price'  => $lineTotal,
        ];
    }

    if (empty($orderItems)) {
        header('Location: keranjang.php');
        exit;
    }

    // Save the order and its items inside a transaction
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (user_name, address, phone, total_price, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$user_name, $address, $phone, $total]);
    $order_id = (int)$pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, total_price) VALUES (?, ?, ?, ?, ?)");
    foreach ($orderItems as $item) {
        $stmt->execute([
            $order_id,
            $item['product_id'],
            $item['product_name'],
            $item['quantity'],
            $item['total_price'],
        ]);
    }

    $pdo->commit();

    // Empty the cart after a successful checkout
    $_SESSION['cart'] = [];
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['checkout_errors'] = ['Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.'];
    header('Location: checkout.php');
    exit;
}

// Redirect to the invoice page
header('Location: invoice.php?id=' . $order_id);
exit;
?>
