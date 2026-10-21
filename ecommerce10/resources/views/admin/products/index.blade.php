<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-md">Add Product</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (session('success'))
                    <div class="mx-6 mt-6 rounded-md bg-green-50 p-4 text-sm text-green-700" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Filter and Search section --}}
                <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-end mb-4 gap-2 p-6">
                    {{-- Search section --}}
                    <div class="flex flex-col items-start gap-1">
                        <label for="search" class="mr-2">Search:</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" class="border-gray-300 rounded-md">
                    </div>
                    {{-- Filter section --}}
                    <div class="flex flex-col items-start gap-1">
                        <label for="category" class="mr-2">Category:</label>
                        <select id="category" name="product_category_id" class="border-gray-300 rounded-md">
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('product_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Order by section --}}
                    <div class="flex flex-col items-start gap-1">
                        <label for="order" class="mr-2">Order by:</label>
                        <select id="order" name="order" class="border-gray-300 rounded-md">
                            <option value="name" {{ request('order') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('order') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            <option value="price" {{ request('order') == 'price' ? 'selected' : '' }}>Price Low-High</option>
                            <option value="price_desc" {{ request('order') == 'price_desc' ? 'selected' : '' }}>Price High-Low</option>
                            <option value="stock" {{ request('order') == 'stock' ? 'selected' : '' }}>Stock Low-High</option>
                            <option value="stock_desc" {{ request('order') == 'stock_desc' ? 'selected' : '' }}>Stock High-Low</option>
                        </select>
                    </div>
                    <div class="flex flex-col justify-end items-end gap-1">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Filter</button>
                    </div>
                    @if(request()->has('product_category_id') || request()->has('order') || request()->has('search'))
                        <div class="flex flex-col justify-end items-end gap-1">
                            <a href="{{ route('products.index') }}" class="bg-red-500 text-white px-4 py-2 rounded-md">Clear Filters</a>
                        </div>
                    @endif
                </form>
                <div class="p-6 text-gray-900">
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Image
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stock
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="h-16 w-16 object-cover">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->stock }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->product_category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-900 ml-4">Edit</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
