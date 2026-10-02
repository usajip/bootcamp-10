<?php
require '../../koneksi_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    // data validation can be added here if needed
    if (empty($name) || empty($price) || empty($description) || empty($stock)) {
        // handle the error, e.g., redirect back with an error message
        // echo "All fields are required.";
        header('Location: ../form/edit_form.php?id=' . $id);
        exit;
    }

    $sql = "UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $price, $description, $stock, $id]);

    header('Location: ../index.php');
    exit;
}
?>