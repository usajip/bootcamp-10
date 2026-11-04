<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;

it('shows only the logged in user cart items', function () {
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);

    $me = User::factory()->create();
    $other = User::factory()->create();

    $mine = Product::create([
        'name' => 'Clean Code',
        'description' => 'Agile handbook.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 10,
        'image' => 'clean-code.jpg',
        'product_category_id' => $category->id,
    ]);

    $theirs = Product::create([
        'name' => 'Refactoring',
        'description' => 'Improving design.',
        'slug' => 'refactoring',
        'price' => 200000,
        'stock' => 5,
        'image' => 'refactoring.jpg',
        'product_category_id' => $category->id,
    ]);

    Cart::create(['user_id' => $me->id, 'product_id' => $mine->id, 'quantity' => 2]);
    Cart::create(['user_id' => $other->id, 'product_id' => $theirs->id, 'quantity' => 1]);

    $response = $this->actingAs($me)->get(route('cart.index'));

    $response->assertOk()
        ->assertSee('Clean Code')
        ->assertSee('Rp 300.000')
        ->assertDontSee('Refactoring');
});

it('shows the empty state when the cart has no items', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('cart.index'))
        ->assertOk()
        ->assertSee('Keranjang kamu masih kosong');
});

it('redirects guests to login', function () {
    $this->get(route('cart.index'))->assertRedirectContains(route('login'));
});

function createCartTestProduct(string $name, string $slug, int $price, int $stock): Product
{
    $category = ProductCategory::firstOrCreate(['slug' => 'books'], ['name' => 'Books']);

    return Product::create([
        'name' => $name,
        'description' => 'Deskripsi '.$name,
        'slug' => $slug,
        'price' => $price,
        'stock' => $stock,
        'image' => $slug.'.jpg',
        'product_category_id' => $category->id,
    ]);
}

it('updates the quantity of a cart item owned by the user', function () {
    $user = User::factory()->create();
    $product = createCartTestProduct('Clean Code', 'clean-code', 150000, 10);
    $cart = Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

    $response = $this->actingAs($user)->patch(route('cart.update', $cart), ['quantity' => 4]);

    $response->assertRedirect()->assertSessionHas('success');
    expect($cart->fresh()->quantity)->toBe(4);
});

it('rejects a quantity above the product stock', function () {
    $user = User::factory()->create();
    $product = createCartTestProduct('Clean Code', 'clean-code', 150000, 3);
    $cart = Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

    $response = $this->actingAs($user)->patch(route('cart.update', $cart), ['quantity' => 5]);

    $response->assertRedirect()->assertSessionHas('error');
    expect($cart->fresh()->quantity)->toBe(1);
});

it('rejects an invalid quantity', function () {
    $user = User::factory()->create();
    $product = createCartTestProduct('Clean Code', 'clean-code', 150000, 10);
    $cart = Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

    $this->actingAs($user)
        ->patch(route('cart.update', $cart), ['quantity' => 0])
        ->assertSessionHasErrors('quantity');

    expect($cart->fresh()->quantity)->toBe(1);
});

it('forbids updating another user cart item', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $product = createCartTestProduct('Clean Code', 'clean-code', 150000, 10);
    $cart = Cart::create(['user_id' => $owner->id, 'product_id' => $product->id, 'quantity' => 1]);

    $this->actingAs($other)->patch(route('cart.update', $cart), ['quantity' => 2])->assertForbidden();

    expect($cart->fresh()->quantity)->toBe(1);
});

it('deletes a cart item owned by the user', function () {
    $user = User::factory()->create();
    $product = createCartTestProduct('Clean Code', 'clean-code', 150000, 10);
    $cart = Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);

    $response = $this->actingAs($user)->delete(route('cart.destroy', $cart));

    $response->assertRedirect()->assertSessionHas('success');
    expect(Cart::find($cart->id))->toBeNull();
});

it('forbids deleting another user cart item', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $product = createCartTestProduct('Clean Code', 'clean-code', 150000, 10);
    $cart = Cart::create(['user_id' => $owner->id, 'product_id' => $product->id, 'quantity' => 2]);

    $this->actingAs($other)->delete(route('cart.destroy', $cart))->assertForbidden();

    expect(Cart::find($cart->id))->not->toBeNull();
});

it('requires authentication to update or delete cart items', function () {
    $user = User::factory()->create();
    $product = createCartTestProduct('Clean Code', 'clean-code', 150000, 10);
    $cart = Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);

    $this->patch(route('cart.update', $cart), ['quantity' => 3])->assertRedirectContains(route('login'));
    $this->delete(route('cart.destroy', $cart))->assertRedirectContains(route('login'));

    expect($cart->fresh()->quantity)->toBe(2);
});
