<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('product_category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->product_category_id, function ($query, $product_category_id) {
                $query->where('product_category_id', $product_category_id);
            })
            ->when($request->order, function ($query, $order) {
                switch ($order) {
                    case 'name':
                        $query->orderBy('name', 'asc');
                        break;
                    case 'name_desc':
                        $query->orderBy('name', 'desc');
                        break;
                    case 'price':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'stock':
                        $query->orderBy('stock', 'asc');
                        break;
                    case 'stock_desc':
                        $query->orderBy('stock', 'desc');
                        break;
                }
            })
            ->paginate(10);
        $categories = ProductCategory::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('product.description', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
