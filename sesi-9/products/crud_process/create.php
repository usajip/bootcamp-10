<?php
require '../../koneksi_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // data validation can be added here if needed
    if (empty($name) || empty($price) || empty($description) || empty($_FILES['image']['name']) || empty($_POST['category'])) {
        // handle the error, e.g., redirect back with an error message
        // echo "Semua field harus diisi!";
        header('Location: ../form/create_form.php');
        exit;
    }

    // image file validation
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
    $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo "Hanya file gambar dengan ekstensi .jpg, .jpeg, .png, .webp, .avif yang diperbolehkan!";
        exit;
    }

    $fileSize = $_FILES['image']['size'] / 1024 / 1024; // in MB
    if ($fileSize > 1) {
        echo "Ukuran file gambar tidak boleh lebih dari 1MB!";
        exit;
    }

    $category = trim($_POST['category']);
    $image = $_FILES['image']['name'];
    $targetDir = __DIR__ . "/../../uploads/";
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // rename file with unique name
    $image = uniqid() . '_' . $image;
    $targetFile = $targetDir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $sql = "INSERT INTO products (name, price, description, category, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $price, $description, $category, $image]);
    } else {
        // handle the error, e.g., redirect back with an error message
        // header('Location: ../form/create_form.php');
        echo "Gagal mengunggah gambar!";
        exit;
    }

    header('Location: ../index.php');
    exit;
}
?>