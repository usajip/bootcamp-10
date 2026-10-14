<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('price', '>', 100000)
                        ->orderBy('price', 'desc')
                        ->with('product_category')
                        ->paginate(12);
        
        $features = [
        ['icon' => '🚚', 'title' => 'Gratis Ongkir', 'text' => 'Untuk pembelian di atas Rp 100.000.'],
        ['icon' => '🔒', 'title' => 'Pembayaran Aman', 'text' => 'Beragam metode pembayaran terpercaya.'],
        ['icon' => '↩️', 'title' => 'Garansi 7 Hari', 'text' => 'Retur mudah jika produk tidak sesuai.'],
        ['icon' => '💬', 'title' => 'Layanan 24/7', 'text' => 'Tim kami siap membantu kapan saja.'],
    ];

        $categories = ProductCategory::all();
        return view('home.index', compact('features', 'categories', 'products'));
        // return view('home.index', ['pengguna' => $users]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($category) {
            $category = trim($category);
            $query->whereHas('product_category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        $products = $query->with('product_category')->paginate(12);

        $categories = ProductCategory::all();

        return view('home.search', compact('products', 'search', 'category', 'categories'));
    }
}
