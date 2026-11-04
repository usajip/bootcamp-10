<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect_to' => request()->fullUrl()]);
        }

        $cartItems = Cart::with('product.product_category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $totalQuantity = $cartItems->sum('quantity');
        $totalPrice = $cartItems->sum(fn (Cart $item) => $item->quantity * ($item->product->price ?? 0));

        return view('cart.index', compact('cartItems', 'totalQuantity', 'totalPrice'));
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
    public function store(Request $request, Product $product)
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect_to' => url()->previous()]);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // check if the product is already in the cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
        if ($cartItem) {
            // check the product stock before updating the quantity
            if ($request->quantity + $cartItem->quantity > $product->stock) {
                return back()->with('error', 'Stok di keranjang kamu melebihi stok tersedia')->withInput();
            }
            // if it exists, update the quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // check the product stock before creating the cart item
            if ($request->quantity > $product->stock) {
                return back()->with('error', 'Stok di keranjang kamu melebihi stok tersedia')->withInput();
            }
            // if it doesn't exist, create a new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        if ($redirect = $this->guardCart($cart)) {
            return $redirect;
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ], [
            'quantity.required' => 'Jumlah produk wajib diisi.',
            'quantity.integer' => 'Jumlah produk harus berupa angka.',
            'quantity.min' => 'Jumlah produk minimal 1.',
        ]);

        $stock = $cart->product->stock;
        if ($request->quantity > $stock) {
            return back()->with('error', "Jumlah melebihi stok tersedia ({$stock}).");
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return back()->with('success', 'Jumlah produk di keranjang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        if ($redirect = $this->guardCart($cart)) {
            return $redirect;
        }

        $cart->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Redirect guests to login and block access to other users' cart items.
     */
    private function guardCart(Cart $cart): ?RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect_to' => url()->previous()]);
        }

        abort_unless($cart->user_id === Auth::id(), 403);

        return null;
    }
}
