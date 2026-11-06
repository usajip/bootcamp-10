<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == 'admin') {
            $orders = Order::with('user')
                        ->withCount('order_items')
                        ->paginate(10);
        } else {
            $orders = Order::where('user_id', Auth::id())
                        ->withCount('order_items')
                        ->paginate(10);
        }
        return view('orders.index', compact('orders'));
    }
    /**
     * Checkout seluruh isi keranjang user yang sedang login menjadi sebuah order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'shipping_address.min' => 'Alamat pengiriman minimal 10 karakter.',
            'shipping_address.max' => 'Alamat pengiriman maksimal 1000 karakter.',
        ]);

        $userId = Auth::id();

        $order = DB::transaction(function () use ($userId, $validated) {
            $cartItems = Cart::with('product')
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang Anda kosong.',
                ]);
            }

            // Lock product rows so stock is checked and decremented atomically.
            $products = Product::whereIn('id', $cartItems->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cartItems as $item) {
                $product = $products->get($item->product_id);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'cart' => 'Ada produk di keranjang yang sudah tidak tersedia.',
                    ]);
                }

                if ($item->quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->name} tidak mencukupi (tersedia {$product->stock}).",
                    ]);
                }
            }

            $totalAmount = $cartItems->sum(fn (Cart $item) => $item->quantity * $item->product->price);

            $order = Order::create([
                'order_number' => 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_address' => $validated['shipping_address'],
                'user_id' => $userId,
            ]);

            foreach ($cartItems as $item) {
                $product = $products->get($item->product_id);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                ]);

                $product->decrement('stock', $item->quantity);
            }

            Cart::where('user_id', $userId)->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Checkout berhasil. Pesanan Anda sedang diproses.');
    }

    /**
     * Menampilkan detail order. Admin bisa melihat semua order, user hanya order miliknya.
     */
    public function show(string $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        $isAdmin = Auth::user()->role === 'admin';

        abort_unless($isAdmin || $order->user_id === Auth::id(), 404);

        $order->load('order_items.product');

        return view('orders.show', [
            'order' => $order,
            'statuses' => Order::STATUSES,
            'paymentStatuses' => Order::PAYMENT_STATUSES,
            'whatsappUrl' => $this->whatsappConfirmationUrl($order),
        ]);
    }

    /**
     * Bentuk tautan wa.me berisi ringkasan pesanan untuk konfirmasi ke toko.
     */
    private function whatsappConfirmationUrl(Order $order): ?string
    {
        $number = preg_replace('/\D+/', '', (string) config('services.whatsapp.number'));

        if ($number === '') {
            return null;
        }

        $lines = [
            'Halo, saya ingin konfirmasi pesanan:',
            "No. Pesanan: {$order->order_number}",
        ];

        foreach ($order->order_items as $item) {
            $name = $item->product->name ?? 'Produk tidak tersedia';
            $lines[] = "- {$name} x{$item->quantity} = Rp ".number_format($item->price * $item->quantity, 0, ',', '.');
        }

        $lines[] = 'Total: Rp '.number_format($order->total_amount, 0, ',', '.');
        $lines[] = "Alamat pengiriman: {$order->shipping_address}";

        return 'https://wa.me/'.$number.'?text='.rawurlencode(implode("\n", $lines));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update status pesanan dan status pembayaran (khusus admin).
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(Order::STATUSES)],
            'payment_status' => ['required', 'string', Rule::in(Order::PAYMENT_STATUSES)],
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status pesanan tidak valid.',
            'payment_status.required' => 'Status pembayaran wajib dipilih.',
            'payment_status.in' => 'Status pembayaran tidak valid.',
        ]);

        $order->update($validated);

        return redirect()
            ->route('orders.show', $order->order_number)
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
