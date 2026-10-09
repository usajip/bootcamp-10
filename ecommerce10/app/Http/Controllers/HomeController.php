<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $features = [
        ['icon' => '🚚', 'title' => 'Gratis Ongkir', 'text' => 'Untuk pembelian di atas Rp 100.000.'],
        ['icon' => '🔒', 'title' => 'Pembayaran Aman', 'text' => 'Beragam metode pembayaran terpercaya.'],
        ['icon' => '↩️', 'title' => 'Garansi 7 Hari', 'text' => 'Retur mudah jika produk tidak sesuai.'],
        ['icon' => '💬', 'title' => 'Layanan 24/7', 'text' => 'Tim kami siap membantu kapan saja.'],
    ];

    $categories = [
        ['icon' => '📱', 'name' => 'Elektronik'],
        ['icon' => '👕', 'name' => 'Fashion'],
        ['icon' => '🏠', 'name' => 'Rumah Tangga'],
        ['icon' => '💄', 'name' => 'Kecantikan'],
        ['icon' => '👟', 'name' => 'Olahraga'],
        ['icon' => '📚', 'name' => 'Buku'],
    ];

    $products = [
        ['name' => 'Kaos Polos Premium', 'category' => 'Fashion', 'price' => 89000, 'description' => 'Kaos katun combed 30s yang nyaman dipakai harian.'],
        ['name' => 'Sepatu Lari Sport', 'category' => 'Olahraga', 'price' => 525000, 'description' => 'Ringan dan empuk untuk lari jarak jauh.'],
        ['name' => 'Tas Ransel Anti Air', 'category' => 'Aksesoris', 'price' => 249000, 'description' => 'Berkapasitas 25 liter dengan bahan tahan air.'],
        ['name' => 'Headphone Bluetooth', 'category' => 'Elektronik', 'price' => 349000, 'description' => 'Baterai tahan 30 jam dengan suara jernih.'],
        ['name' => 'Botol Minum Termos', 'category' => 'Rumah Tangga', 'price' => 129000, 'description' => 'Menjaga suhu minuman hingga 12 jam.'],
        ['name' => 'Jam Tangan Digital', 'category' => 'Aksesoris', 'price' => 415000, 'description' => 'Layar AMOLED dengan pelacak aktivitas.'],
        ['name' => 'Kemeja Flanel Casual', 'category' => 'Fashion', 'price' => 189000, 'description' => 'Bahan lembut dengan motif kotak klasik.'],
        ['name' => 'Lampu Meja LED', 'category' => 'Rumah Tangga', 'price' => 99000, 'description' => 'Cahaya hangat dengan tiga tingkat kecerahan.'],
    ];
        return view('home.index', compact('features', 'categories', 'products'));
        // return view('home.index', ['pengguna' => $users]);
    }

    public function index2()
    {
        return view('home.index2');
    }

    public function indexLayoutComponent($id)
    {
        return view('home.index-layout-component', compact('id'));
    }
}
