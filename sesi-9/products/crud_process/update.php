<?php
require '../../koneksi_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    // data validation can be added here if needed
    if (empty($id) || empty($name) || empty($price) || empty($description) || empty($category)) {
        // handle the error, e.g., redirect back with an error message
        header('Location: ../form/edit_form.php?id=' . $id);
        exit;
    }

    // keep the existing image by default
    $image = $_POST['current_image'] ?? null;

    // handle new image upload if provided
    if (!empty($_FILES['image']['name'])) {
        $targetDir = __DIR__ . "/../../uploads/";

        // Create uploads directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
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

        // rename file with unique name
        $image = uniqid() . '_' . $_FILES['image']['name'];
        $targetFile = $targetDir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // remove the old image if it exists
            if (!empty($_POST['current_image']) && file_exists($targetDir . $_POST['current_image'])) {
                unlink($targetDir . $_POST['current_image']);
            }
        } else {
            // handle the error, e.g., redirect back with an error message
            echo "Gagal mengunggah gambar!";
            exit;
        }
    }

    $sql = "UPDATE products SET name = ?, price = ?, description = ?, category = ?, image = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $price, $description, $category, $image, $id]);

    header('Location: ../index.php');
    exit;
}
?>