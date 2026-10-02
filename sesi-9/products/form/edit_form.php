<?php
require '../../koneksi_db.php'; // include the database connection file

// Get the product ID from the query string
$id = $_GET['id'];

// Read the product data
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$product = $stmt->fetch();

// redirect back to list if product not found
if (!$product) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Produk</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="mb-4">Edit Produk</h1>
                    <form action="../crud_process/update.php" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga:</label>
                            <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi:</label>
                            <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        <!-- Category input -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori:</label>
                            <?php
                                $categories = ["Daily Needs", "Electronics", "Fashion", "Food & Beverage", "Furniture", "Home Decor", "Makanan"];
                            ?>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category; ?>" <?php echo ($product['category'] === $category) ? 'selected' : ''; ?>><?php echo ucfirst($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar:</label>
                            <?php if (!empty($product['image'])): ?>
                                <div class="mb-2">
                                    <img src="../../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Gambar Produk" style="max-width: 150px; max-height: 150px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="../index.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function validateForm() {
                var price = document.getElementById('price').value;
                var imageInput = document.getElementById('image');

                if (!requiredField('name')) return false;
                if (!requiredField('price')) return false;
                if (!requiredField('description')) return false;
                if (!requiredField('category')) return false;

                if (isNaN(price)) {
                    alert("Harga harus berupa angka!");
                    return false;
                }

                // validate image file type (only if a new file is selected)
                if (imageInput.files.length > 0) {
                    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.webp|\.avif)$/i;
                    if (!allowedExtensions.exec(imageInput.value)) {
                        alert("Hanya file gambar dengan ekstensi .jpg, .jpeg, .png, .webp, .avif yang diperbolehkan!");
                        return false;
                    }

                    // validate image file size
                    var fileSize = imageInput.files[0].size / 1024 / 1024; // in MB
                    if (fileSize > 1) {
                        alert("Ukuran file gambar tidak boleh lebih dari 1MB!");
                        return false;
                    }
                }
                return true;
            }

            function requiredField(fieldId) {
                var field = document.getElementById(fieldId);
                if (!field || field.value.trim() === "") {
                    alert("Field " + fieldId + " harus diisi!");
                    return false;
                }
                return true;
            }
        </script>
    </body>
</html>