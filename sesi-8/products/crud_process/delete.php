<?php
require '../../koneksi_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // data validation can be added here if needed
    if (empty($id)) {
        // handle the error, e.g., redirect back with an error message
        header('Location: ../index.php');
        exit;
    }

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header('Location: ../index.php');
    exit;
}else{
    // handle the case when the request method is not POST
    header('Location: ../index.php');
    exit;
}
?>