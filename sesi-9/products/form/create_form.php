<!DOCTYPE html>
<html>
    <head>
        <title>Tambah Produk</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="mb-4">Tambah Produk</h1>
                    <form action="../crud_process/create.php" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga:</label>
                            <input type="text" class="form-control" id="price" name="price">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi:</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
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
                                    <option value="<?php echo $category; ?>"><?php echo ucfirst($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar:</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function validateForm() {
                var name = document.getElementById('name').value;
                var price = document.getElementById('price').value;
                var description = document.getElementById('description').value;
                var stock = document.getElementById('stock') ? document.getElementById('stock').value : "";
                var category = document.getElementById('category') ? document.getElementById('category').value : "";

                requiredField('name');
                requiredField('price');
                requiredField('description');
                requiredField('category');
                requiredField('image');

                if (isNaN(price) || isNaN(stock)) {
                    alert("Harga dan Stok harus berupa angka!");
                    return false;
                }

                var imageInput = document.getElementById('image');

                if (imageInput.value === "") {
                    alert("Gambar harus diunggah!");
                    return false;
                }

                // validate image file type
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.webp|\.avif)$/i;
                if (!allowedExtensions.exec(imageInput.value)) {
                    alert("Hanya file gambar dengan ekstensi .jpg, .jpeg, .png, .webp, .avif yang diperbolehkan!");
                    return false;
                }

                // validate image file size
                if (imageInput.files.length > 0) {
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