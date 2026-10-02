<?php
// menampilkan daftar produk
require '../koneksi_db.php'; // include the database connection file

// Read
$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Daftar Produk</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="form/create_form.php" class="btn btn-primary">Tambah Produk Baru</a>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Thumbnail</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo 'Rp ' . number_format($product['price'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>
                            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Thumbnail" style="max-width: 100px; max-height: 100px;">
                        </td>
                        <td>
                            <a href="form/edit_form.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="crud_process/delete.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <input type="submit" class="btn btn-sm btn-danger" value="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>