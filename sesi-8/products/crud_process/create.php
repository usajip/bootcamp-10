<?php
require '../../koneksi_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $stock = trim($_POST['stock']);

    // data validation can be added here if needed
    if (empty($name) || empty($price) || empty($description) || empty($stock)) {
        // handle the error, e.g., redirect back with an error message
        header('Location: ../form/create_form.php');
        exit;
    }


    $sql = "INSERT INTO products (name, price, description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $price, $description]);

    header('Location: ../index.php');
    exit;
}
?>