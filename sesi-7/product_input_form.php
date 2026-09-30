<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Produk</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        .required {
            color: #e74c3c;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="email"]:focus,
        input[type="file"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row-full {
            grid-column: 1 / -1;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 20px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .reset-btn {
            background: #95a5a6;
            margin-top: 10px;
        }

        .reset-btn:hover {
            background: #7f8c8d;
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.4);
        }

        .file-input-label {
            display: block;
            padding: 12px;
            background-color: #f5f5f5;
            border: 2px dashed #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background-color: #efefef;
            border-color: #667eea;
        }

        input[type="file"] {
            display: none;
        }

        .file-name {
            margin-top: 8px;
            font-size: 13px;
            color: #666;
        }

        .info-text {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 25px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Form Input Produk</h1>
        
        <form action="product_input_process.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_produk">Nama Produk <span class="required">*</span></label>
                    <input type="text" id="nama_produk" name="nama_produk" placeholder="Masukkan nama produk" required>
                </div>

                <div class="form-group">
                    <label for="harga">Harga <span class="required">*</span></label>
                    <input type="number" id="harga" name="harga" placeholder="Masukkan harga" min="0" required>
                </div>
            </div>

            <div class="form-group form-row-full">
                <label for="deskripsi">Deskripsi <span class="required">*</span></label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi produk..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kategori">Kategori <span class="required">*</span></label>
                    <select id="kategori" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Makanan & Minuman">Makanan & Minuman</option>
                        <option value="Peralatan Rumah Tangga">Peralatan Rumah Tangga</option>
                        <option value="Buku">Buku</option>
                        <option value="Olahraga">Olahraga</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stok">Stok <span class="required">*</span></label>
                    <input type="number" id="stok" name="stok" placeholder="Masukkan jumlah stok" min="0" required>
                </div>
            </div>

            <div class="form-group form-row-full">
                <label for="gambar">Gambar Produk <span class="required">*</span></label>
                <label for="gambar" class="file-input-label">
                    <div>📷 Klik untuk memilih gambar</div>
                    <div class="file-name" id="file-name"></div>
                </label>
                <input type="file" id="gambar" name="gambar" accept="image/*" required onchange="handleFileSelect()">
                <div class="info-text">Format: JPG, PNG, GIF (Max 5MB)</div>
            </div>

            <button type="submit">Kirim Produk</button>
            <button type="reset" class="reset-btn">Reset Form</button>
        </form>
    </div>

    <script>
        function validateForm() {
            const nama_produk = document.getElementById('nama_produk').value.trim();
            const harga = document.getElementById('harga').value.trim();
            const deskripsi = document.getElementById('deskripsi').value.trim();
            const kategori = document.getElementById('kategori').value.trim();
            const stok = document.getElementById('stok').value.trim();
            const gambar = document.getElementById('gambar').files;

            // Validasi semua field tidak kosong
            if (!nama_produk) {
                alert('❌ Nama produk tidak boleh kosong!');
                return false;
            }

            if (!harga || harga <= 0) {
                alert('❌ Harga harus lebih dari 0!');
                return false;
            }

            if (!deskripsi) {
                alert('❌ Deskripsi tidak boleh kosong!');
                return false;
            }

            if (!kategori) {
                alert('❌ Silakan pilih kategori!');
                return false;
            }

            if (!stok || stok < 0) {
                alert('❌ Stok tidak boleh kosong atau negatif!');
                return false;
            }

            if (gambar.length === 0) {
                alert('❌ Silakan pilih gambar produk!');
                return false;
            }

            // Validasi ukuran file (1MB = 1.048.576 bytes)
            if (gambar[0].size > 1048576) {
                alert('❌ Ukuran gambar tidak boleh lebih dari 1MB!');
                return false;
            }

            // Validasi format file
            const allowedFormats = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg', 'image/avif'];
            if (!allowedFormats.includes(gambar[0].type)) {
                alert('❌ Format gambar harus JPG, PNG, GIF, AVIF, atau WebP!');
                return false;
            }

            return true;
        }

        function handleFileSelect() {
            const fileInput = document.getElementById('gambar');
            const fileName = document.getElementById('file-name');
            
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                fileName.textContent = `✓ ${file.name} (${fileSizeMB}MB)`;
            }
        }
    </script>
</body>
</html>
