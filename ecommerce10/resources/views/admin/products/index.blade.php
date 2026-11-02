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
                @include('admin.partials.success-error-info')

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
                                        Sold
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
                                            <a href="{{ route('products.show', $product->slug) }}" class="text-indigo-600 hover:text-indigo-900">{{ $product->name }}</a>
                                        </td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ Str::limit($product->description, 10) }}
                                        </td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 max-w-[200px] text-ellipsis overflow-hidden">
                                            {{ $product->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->stock }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->order_items_sum_quantity ?? 0 }} pcs
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->product_category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-900 ml-4">Edit</a>
                                            <a href="#!" x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'delete-product-modal{{ $product->id }}')" 
                                            class="text-red-600 hover:text-red-900 ml-4">Delete</a>
                                        </td>
                                    </tr>
                                    @push('scripts')
                                    <x-modal name="delete-product-modal{{ $product->id }}" max-width="lg" focusable>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="p-6">
                                            @csrf
                                            @method('DELETE')
                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ 'Delete Product: ' . $product->name }}
                                            </h2>
                                            <p class="mt-4">Are you sure you want to delete this product?</p>
                                            <div class="mt-4 flex justify-between gap-4">
                                                <button type="button" x-on:click="$dispatch('close-modal', 'delete-product-modal{{ $product->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                                            </div>
                                        </form>
                                    </x-modal>
                                    @endpush
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
