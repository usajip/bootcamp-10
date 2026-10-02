<!DOCTYPE html>
<html>
    <head>
        <title>Tambah Produk</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../styles.css">
    </head>
    <body>
        <h1>Tambah Produk</h1>
        <form action="../crud_process/create.php" onsubmit="return validateForm()" method="post">
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name"><br>
            <label for="price">Harga:</label>
            <input type="text" id="price" name="price"><br>
            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description"></textarea><br>
            <input type="submit" value="Tambah">
        </form>
        <script>
            function validateForm() {
                var name = document.getElementById('name').value;
                var price = document.getElementById('price').value;
                var description = document.getElementById('description').value;
                var stock = document.getElementById('stock').value;

                if (name === "" || price === "" || description === "" || stock === "") {
                    alert("Semua field harus diisi!");
                    return false;
                }
                if (isNaN(price) || isNaN(stock)) {
                    alert("Harga dan Stok harus berupa angka!");
                    return false;
                }
                return true;
            }
        </script>
    </body>
</html>