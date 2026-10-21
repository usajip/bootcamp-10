<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
        $categories = ProductCategory::all();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->productRules(imageRequired: true));

        $imageDirectory = public_path('images/products');
        File::ensureDirectoryExists($imageDirectory);

        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->move($imageDirectory, $imageName);

        Product::create([
            ...$validated,
            'slug' => $this->uniqueSlug($validated['name']),
            'image' => $imageName,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Validation rules shared by store() and update().
     *
     * @return array<string, array<int, mixed>>
     */
    private function productRules(bool $imageRequired): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:3', 'max:1000'],
            'price' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'stock' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'image' => [
                $imageRequired ? 'required' : 'nullable',
                'image', 'mimes:jpeg,jpg,png,webp', 'max:2048', 'dimensions:min_width=300,min_height=300',
            ],
        ];
    }

    /**
     * Build a unique slug from the given product name.
     */
    private function uniqueSlug(string $name, ?int $ignoreProductId = null): string
    {
        $slug = Str::slug($name) ?: 'product';
        $baseSlug = $slug;
        $counter = 2;

        while (Product::where('slug', $slug)->when($ignoreProductId, fn ($query, $id) => $query->whereKeyNot($id))->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
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
    // public function edit($id)
    {
        // $product = Product::findOrFail($id);
        $categories = ProductCategory::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->productRules(imageRequired: false));

        $data = collect($validated)->except('image')->all();

        if ($validated['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($validated['name'], $product->id);
        }

        $oldImage = $product->image;

        if ($request->hasFile('image')) {
            $imageDirectory = public_path('images/products');
            File::ensureDirectoryExists($imageDirectory);

            $image = $request->file('image');
            $data['image'] = $image->hashName();
            $image->move($imageDirectory, $data['image']);
        }

        $product->update($data);

        if (isset($data['image']) && $oldImage && $oldImage !== 'default.jpg') {
            File::delete(public_path('images/products/'.$oldImage));
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
