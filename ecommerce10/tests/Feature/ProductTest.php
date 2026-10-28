<?php

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

it('creates a product with the uploaded image', function () {
    $user = User::factory()->create();
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);

    $response = $this->actingAs($user)->post(route('products.store'), [
        'name' => 'Clean Code',
        'description' => 'A handbook of agile software craftsmanship.',
        'price' => 150000,
        'stock' => 10,
        'product_category_id' => $category->id,
        'image' => UploadedFile::fake()->image('clean-code.jpg', 600, 600),
    ]);

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHas('success');

    $product = Product::where('name', 'Clean Code')->first();

    expect($product)->not->toBeNull()
        ->and($product->slug)->toBe('clean-code')
        ->and($product->image)->not->toBe('clean-code.jpg')
        ->and(File::exists(public_path('images/products/'.$product->image)))->toBeTrue();

    File::delete(public_path('images/products/'.$product->image));
});

it('generates a unique slug for duplicate product names', function () {
    $user = User::factory()->create();
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);

    Product::create([
        'name' => 'Clean Code',
        'description' => 'First copy.',
        'slug' => 'clean-code',
        'price' => 100000,
        'stock' => 1,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    $this->actingAs($user)->post(route('products.store'), [
        'name' => 'Clean Code',
        'description' => 'Second copy.',
        'price' => 150000,
        'stock' => 10,
        'product_category_id' => $category->id,
        'image' => UploadedFile::fake()->image('clean-code.jpg', 600, 600),
    ])->assertRedirect(route('products.index'));

    $product = Product::where('description', 'Second copy.')->first();

    expect($product->slug)->toBe('clean-code-2');

    File::delete(public_path('images/products/'.$product->image));
});

it('validates every product input', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from(route('products.create'))
        ->post(route('products.store'), [
            'name' => 'ab',
            'description' => 'ab',
            'price' => -1,
            'stock' => -1,
            'product_category_id' => 999,
            'image' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]);

    $response->assertRedirect(route('products.create'));
    $response->assertSessionHasErrors(['name', 'description', 'price', 'stock', 'product_category_id', 'image']);

    expect(Product::count())->toBe(0);
});

it('requires authentication to create a product', function () {
    $this->post(route('products.store'))->assertRedirect(route('login'));
});

it('renders the create product form', function () {
    $user = User::factory()->create();
    ProductCategory::create(['name' => 'Books', 'slug' => 'books']);

    $this->actingAs($user)
        ->get(route('products.create'))
        ->assertOk()
        ->assertSee('id="product-form"', false)
        ->assertSee('id="image-cropper"', false);
});

it('renders the edit product form prefilled with the product data', function () {
    $user = User::factory()->create();
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);
    $product = Product::create([
        'name' => 'Clean Code',
        'description' => 'A handbook.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 10,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->get(route('products.edit', $product))
        ->assertOk()
        ->assertSee('id="product-form"', false)
        ->assertSee('value="Clean Code"', false)
        ->assertSee('name="_method" value="PUT"', false)
        ->assertSee(asset('images/products/default.jpg'), false);
});

it('updates a product without requiring a new image', function () {
    $user = User::factory()->create();
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);
    $product = Product::create([
        'name' => 'Clean Code',
        'description' => 'A handbook.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 10,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->put(route('products.update', $product), [
            'name' => 'Clean Code',
            'description' => 'Updated description.',
            'price' => 175000,
            'stock' => 5,
            'product_category_id' => $category->id,
        ])
        ->assertRedirect(route('products.index'))
        ->assertSessionHas('success');

    $product->refresh();

    expect($product->description)->toBe('Updated description.')
        ->and($product->price)->toBe(175000)
        ->and($product->stock)->toBe(5)
        ->and($product->slug)->toBe('clean-code')
        ->and($product->image)->toBe('default.jpg');
});

it('regenerates a unique slug when the product name changes', function () {
    $user = User::factory()->create();
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);
    Product::create([
        'name' => 'Refactoring',
        'description' => 'Another book.',
        'slug' => 'refactoring',
        'price' => 100000,
        'stock' => 1,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);
    $product = Product::create([
        'name' => 'Clean Code',
        'description' => 'A handbook.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 10,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->put(route('products.update', $product), [
            'name' => 'Refactoring',
            'description' => 'A handbook.',
            'price' => 150000,
            'stock' => 10,
            'product_category_id' => $category->id,
        ])
        ->assertRedirect(route('products.index'));

    expect($product->refresh()->slug)->toBe('refactoring-2');
});

it('replaces the product image and removes the old file', function () {
    $user = User::factory()->create();
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);

    $oldImageName = 'old-product-image-test.jpg';
    File::ensureDirectoryExists(public_path('images/products'));
    File::put(public_path('images/products/'.$oldImageName), 'old');

    $product = Product::create([
        'name' => 'Clean Code',
        'description' => 'A handbook.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 10,
        'image' => $oldImageName,
        'product_category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->put(route('products.update', $product), [
            'name' => 'Clean Code',
            'description' => 'A handbook.',
            'price' => 150000,
            'stock' => 10,
            'product_category_id' => $category->id,
            'image' => UploadedFile::fake()->image('new.jpg', 600, 600),
        ])
        ->assertRedirect(route('products.index'));

    $product->refresh();

    expect($product->image)->not->toBe($oldImageName)
        ->and(File::exists(public_path('images/products/'.$product->image)))->toBeTrue()
        ->and(File::exists(public_path('images/products/'.$oldImageName)))->toBeFalse();

    File::delete(public_path('images/products/'.$product->image));
});

it('validates every product input when updating', function () {
    $user = User::factory()->create();
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);
    $product = Product::create([
        'name' => 'Clean Code',
        'description' => 'A handbook.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 10,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->from(route('products.edit', $product))
        ->put(route('products.update', $product), [
            'name' => 'ab',
            'description' => 'ab',
            'price' => -1,
            'stock' => -1,
            'product_category_id' => 999,
            'image' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ])
        ->assertRedirect(route('products.edit', $product))
        ->assertSessionHasErrors(['name', 'description', 'price', 'stock', 'product_category_id', 'image']);

    expect($product->refresh()->name)->toBe('Clean Code');
});

it('requires authentication to update a product', function () {
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);
    $product = Product::create([
        'name' => 'Clean Code',
        'description' => 'A handbook.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 10,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    $this->put(route('products.update', $product), [])->assertRedirect(route('login'));
});

it('renders the product detail page with its related products', function () {
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);
    $otherCategory = ProductCategory::create(['name' => 'Toys', 'slug' => 'toys']);

    $product = Product::create([
        'name' => 'Clean Code',
        'description' => 'A handbook of agile software craftsmanship.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 10,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    Product::create([
        'name' => 'Refactoring',
        'description' => 'Improving the design of existing code.',
        'slug' => 'refactoring',
        'price' => 200000,
        'stock' => 5,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    Product::create([
        'name' => 'Lego Set',
        'description' => 'A building block set.',
        'slug' => 'lego-set',
        'price' => 300000,
        'stock' => 5,
        'image' => 'default.jpg',
        'product_category_id' => $otherCategory->id,
    ]);

    $this->get(route('products.show', $product->slug))
        ->assertOk()
        ->assertSee('Beli Clean Code')
        ->assertSee('A handbook of agile software craftsmanship.')
        ->assertSee('Rp 150.000')
        ->assertSee('Stok tersedia: 10')
        ->assertSee('Produk Terkait')
        ->assertSee('Refactoring')
        ->assertDontSee('Lego Set');
});

it('hides the purchase button when the product is out of stock', function () {
    $category = ProductCategory::create(['name' => 'Books', 'slug' => 'books']);
    $product = Product::create([
        'name' => 'Clean Code',
        'description' => 'A handbook.',
        'slug' => 'clean-code',
        'price' => 150000,
        'stock' => 0,
        'image' => 'default.jpg',
        'product_category_id' => $category->id,
    ]);

    $this->get(route('products.show', $product->slug))
        ->assertOk()
        ->assertSee('Stok habis')
        ->assertSee('disabled', false);
});

it('returns a not found response for an unknown product slug', function () {
    $this->get('/products/unknown-product')->assertNotFound();
});
