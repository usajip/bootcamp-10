<?php
    session_start();

    // Set header untuk JSON response jika diperlukan
    header('Content-Type: text/html; charset=utf-8');

    $errors = [];
    $product_data = [];
    $file_path = '';

    // Cek apakah form dikirim
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // ============= VALIDASI DATA =============
        
        // 1. Validasi Nama Produk
        $nama_produk = isset($_POST['nama_produk']) ? trim($_POST['nama_produk']) : '';
        if (empty($nama_produk)) {
            $errors[] = "Nama produk tidak boleh kosong";
        } elseif (strlen($nama_produk) > 100) {
            $errors[] = "Nama produk maksimal 100 karakter";
        } else {
            $product_data['nama_produk'] = htmlspecialchars($nama_produk);
        }
        
        // 2. Validasi Harga
        $harga = isset($_POST['harga']) ? trim($_POST['harga']) : '';
        if (empty($harga)) {
            $errors[] = "Harga tidak boleh kosong";
        } elseif (!is_int($harga) || $harga <= 0) {
            $errors[] = "Harga harus berupa angka dan lebih dari 0";
        } else {
            $product_data['harga'] = (int)$harga;
        }
        
        // 3. Validasi Deskripsi
        $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
        if (empty($deskripsi)) {
            $errors[] = "Deskripsi tidak boleh kosong";
        } elseif (strlen($deskripsi) > 500) {
            $errors[] = "Deskripsi maksimal 500 karakter";
        } else {
            $product_data['deskripsi'] = htmlspecialchars($deskripsi);
        }
        
        // 4. Validasi Kategori
        $kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
        $kategori_valid = ['Elektronik', 'Fashion', 'Makanan & Minuman', 'Peralatan Rumah Tangga', 'Buku', 'Olahraga', 'Lainnya'];
        
        if (empty($kategori)) {
            $errors[] = "Kategori tidak boleh kosong";
        } elseif (!in_array($kategori, $kategori_valid)) {
            $errors[] = "Kategori tidak valid";
        } else {
            $product_data['kategori'] = htmlspecialchars($kategori);
        }
        
        // 5. Validasi Stok
        $stok = isset($_POST['stok']) ? trim($_POST['stok']) : '';
        if (empty($stok)) {
            $errors[] = "Stok tidak boleh kosong";
        } elseif (!is_numeric($stok) || $stok < 0) {
            $errors[] = "Stok harus berupa angka dan tidak negatif";
        } else {
            $product_data['stok'] = (int)$stok;
        }
        
        // 6. Validasi File Gambar
        if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = "Gambar produk harus dipilih";
        } else {
            $file = $_FILES['gambar'];
            
            // Cek error upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = "Terjadi kesalahan saat upload gambar";
            } else {
                // Validasi tipe file
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg', 'image/avif'];
                $file_type = mime_content_type($file['tmp_name']);
                
                if (!in_array($file_type, $allowed_types)) {
                    $errors[] = "Format gambar harus JPG, PNG, GIF, AVIF, atau WebP";
                }
                
                // Validasi ukuran file (5MB)
                $max_size = 1 * 1024 * 1024; // 1MB
                if ($file['size'] > $max_size) {
                    $errors[] = "Ukuran gambar tidak boleh lebih dari 1MB";
                }
                
                // Jika validasi file berhasil, simpan info file
                if (empty($errors)) {
                    $product_data['gambar_nama'] = htmlspecialchars($file['name']);
                    $product_data['gambar_size'] = round($file['size'] / 1024, 2) . ' KB';
                    $product_data['gambar_type'] = $file_type;
                    $product_data['gambar_tmp'] = $file['tmp_name'];
                }
            }
        }
        
    } else {
        $errors[] = "Metode request tidak valid";
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Input Produk</title>
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
            max-width: 700px;
        }

        .success {
            background-color: #d4edda;
            border-left: 5px solid #28a745;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .success h2 {
            color: #28a745;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .success-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .error-box {
            background-color: #f8d7da;
            border-left: 5px solid #dc3545;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .error-box h2 {
            color: #dc3545;
            margin-bottom: 15px;
            font-size: 24px;
        }

        .error-list {
            list-style: none;
            padding-left: 0;
        }

        .error-list li {
            padding: 8px 0;
            color: #721c24;
            font-weight: 500;
        }

        .error-list li:before {
            content: "✗ ";
            margin-right: 10px;
            color: #dc3545;
            font-weight: bold;
        }

        .data-display {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .data-item {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #ddd;
        }

        .data-item:last-child {
            border-bottom: none;
        }

        .data-label {
            font-weight: 600;
            color: #667eea;
            font-size: 14px;
            text-transform: uppercase;
        }

        .data-value {
            color: #333;
            word-break: break-word;
            line-height: 1.6;
        }

        .price-highlight {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            font-weight: 600;
            color: #856404;
        }

        .file-info {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 12px;
            border-radius: 4px;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .info-badge {
            display: inline-block;
            background-color: #e7f3ff;
            color: #004085;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
            font-weight: 600;
        }

        .timestamp {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        @media (max-width: 600px) {
            .data-item {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .button-group {
                flex-direction: column;
            }

            .container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (empty($errors) && !empty($product_data)): ?>
            <!-- Tampilan Sukses -->
            <div class="success">
                <div class="success-icon">✓</div>
                <h2>Data Produk Diterima!</h2>
                <p>Form telah diproses dengan sukses. Data ditampilkan di bawah ini:</p>
            </div>

            <div class="data-display">
                <div class="data-item">
                    <div class="data-label">Nama Produk</div>
                    <div class="data-value">
                        <strong><?php echo $product_data['nama_produk']; ?></strong>
                        <div class="info-badge">Produk</div>
                    </div>
                </div>

                <div class="data-item">
                    <div class="data-label">Harga</div>
                    <div class="data-value">
                        <div class="price-highlight">
                            Rp <?php echo number_format($product_data['harga'], 2, ',', '.'); ?>
                        </div>
                    </div>
                </div>

                <div class="data-item">
                    <div class="data-label">Kategori</div>
                    <div class="data-value"><?php echo $product_data['kategori']; ?></div>
                </div>

                <div class="data-item">
                    <div class="data-label">Stok</div>
                    <div class="data-value">
                        <strong><?php echo $product_data['stok']; ?></strong> unit
                    </div>
                </div>

                <div class="data-item">
                    <div class="data-label">Deskripsi</div>
                    <div class="data-value">
                        <?php echo nl2br($product_data['deskripsi']); ?>
                    </div>
                </div>

                <div class="data-item">
                    <div class="data-label">Gambar</div>
                    <div class="data-value">
                        <strong><?php echo $product_data['gambar_nama']; ?></strong>
                        <div class="file-info">
                            📁 Tipe: <?php echo $product_data['gambar_type']; ?><br>
                            💾 Ukuran: <?php echo $product_data['gambar_size']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="timestamp">
                ⏰ Diproses pada: <?php echo date('d-m-Y H:i:s'); ?>
            </div>

            <div class="button-group">
                <a href="product_input_form.php" class="btn btn-primary">🔄 Input Produk Lagi</a>
                <a href="javascript:window.history.back()" class="btn btn-secondary">← Kembali</a>
            </div>

        <?php else: ?>
            <!-- Tampilan Error -->
            <div class="error-box">
                <h2>⚠️ Error!</h2>
                <p>Terjadi kesalahan saat memproses form:</p>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="button-group">
                <a href="product_input_form.php" class="btn btn-primary">← Kembali ke Form</a>
                <a href="javascript:window.history.back()" class="btn btn-secondary">Batal</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
