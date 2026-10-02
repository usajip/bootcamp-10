<?php
require '../../koneksi_db.php'; // include the database connection file

// Get the product ID from the query string
$id = $_GET['id'];

// Read the product data
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$product = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Produk</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../styles.css">
    </head>
    <body>
        <h1>Edit Produk</h1>
        <form action="../crud_process/update.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"><br>
            <label for="price">Harga:</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>"><br>
            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea><br>
            <input type="submit" value="Update">
        </form>
    </body>
</html>