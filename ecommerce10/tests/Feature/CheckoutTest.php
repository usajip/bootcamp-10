<?php

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;

function makeCheckoutProduct(array $overrides = []): Product
{
    $category = ProductCategory::firstOrCreate(['slug' => 'books'], ['name' => 'Books']);

    return Product::create(array_merge([
        'name' => 'Clean Code',
        'description' => 'Agile handbook.',
        'slug' => 'clean-code-'.uniqid(),
        'price' => 150000,
        'stock' => 10,
        'image' => 'clean-code.jpg',
        'product_category_id' => $category->id,
    ], $overrides));
}

it('redirects guests away from checkout', function () {
    $this->post(route('checkout.store'), ['shipping_address' => 'Jl. Merdeka No. 1, Jakarta'])
        ->assertRedirect(route('login'));

    expect(Order::count())->toBe(0);
});

it('creates an order from the cart, decrements stock and clears the cart', function () {
    $user = User::factory()->create();
    $product = makeCheckoutProduct(['price' => 150000, 'stock' => 10]);
    $other = makeCheckoutProduct(['price' => 80000, 'stock' => 4, 'slug' => 'other-product']);

    Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);
    Cart::create(['user_id' => $user->id, 'product_id' => $other->id, 'quantity' => 1]);

    $response = $this->actingAs($user)->post(route('checkout.store'), [
        'shipping_address' => 'Jl. Merdeka No. 1, Jakarta',
    ]);

    $order = Order::firstOrFail();

    $response->assertRedirect(route('orders.show', $order->order_number));

    expect($order->user_id)->toBe($user->id)
        ->and($order->total_amount)->toBe(380000)
        ->and($order->status)->toBe('pending')
        ->and($order->payment_status)->toBe('unpaid')
        ->and($order->shipping_address)->toBe('Jl. Merdeka No. 1, Jakarta')
        ->and($order->order_number)->toStartWith('INV-');

    expect(OrderItem::where('order_id', $order->id)->count())->toBe(2);
    expect(OrderItem::where('order_id', $order->id)->where('product_id', $product->id)->first())
        ->quantity->toBe(2)
        ->price->toBe(150000);

    expect($product->fresh()->stock)->toBe(8);
    expect($other->fresh()->stock)->toBe(3);
    expect(Cart::where('user_id', $user->id)->count())->toBe(0);
});

it('rejects checkout when the cart is empty', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('checkout.store'), ['shipping_address' => 'Jl. Merdeka No. 1, Jakarta'])
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0);
});

it('rejects checkout when stock is insufficient and changes nothing', function () {
    $user = User::factory()->create();
    $product = makeCheckoutProduct(['stock' => 1]);

    Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 3]);

    $this->actingAs($user)
        ->post(route('checkout.store'), ['shipping_address' => 'Jl. Merdeka No. 1, Jakarta'])
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0);
    expect($product->fresh()->stock)->toBe(1);
    expect(Cart::where('user_id', $user->id)->count())->toBe(1);
});

it('requires a shipping address', function () {
    $user = User::factory()->create();
    $product = makeCheckoutProduct();

    Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

    $this->actingAs($user)
        ->post(route('checkout.store'), ['shipping_address' => ''])
        ->assertSessionHasErrors('shipping_address');

    expect(Order::count())->toBe(0);
});

it('shows an order to its owner only', function () {
    $owner = User::factory()->create();
    $stranger = User::factory()->create();

    $order = Order::create([
        'order_number' => 'INV-TEST-000001',
        'total_amount' => 150000,
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'shipping_address' => 'Jl. Merdeka No. 1, Jakarta',
        'user_id' => $owner->id,
    ]);

    $this->actingAs($owner)->get(route('orders.show', $order->order_number))
        ->assertOk()
        ->assertSee('INV-TEST-000001');

    $this->actingAs($stranger)->get(route('orders.show', $order->order_number))
        ->assertForbidden();
});

it('shows a whatsapp confirmation link with the order summary when a number is configured', function () {
    config(['services.whatsapp.number' => '+62 812-3456-7890']);

    $owner = User::factory()->create();
    $product = makeCheckoutProduct(['name' => 'Clean Code', 'price' => 150000]);

    $order = Order::create([
        'order_number' => 'INV-TEST-000002',
        'total_amount' => 300000,
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'shipping_address' => 'Jl. Merdeka No. 1, Jakarta',
        'user_id' => $owner->id,
    ]);
    OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 2, 'price' => 150000]);

    $this->actingAs($owner)->get(route('orders.show', $order->order_number))
        ->assertOk()
        ->assertSee('https://wa.me/6281234567890?text=', false)
        ->assertSee(rawurlencode('No. Pesanan: INV-TEST-000002'), false);
});

it('hides the whatsapp link when no number is configured', function () {
    config(['services.whatsapp.number' => null]);

    $owner = User::factory()->create();
    $order = Order::create([
        'order_number' => 'INV-TEST-000003',
        'total_amount' => 0,
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'shipping_address' => 'Jl. Merdeka No. 1, Jakarta',
        'user_id' => $owner->id,
    ]);

    $this->actingAs($owner)->get(route('orders.show', $order->order_number))
        ->assertOk()
        ->assertDontSee('wa.me');
});
