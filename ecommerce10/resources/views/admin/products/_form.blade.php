@php
    $selectedCategory = old('product_category_id', $product?->product_category_id);
    $currentImageUrl = $product ? asset('images/products/'.$product->image) : null;
@endphp

@include('admin.partials.success-error-info')

<form method="POST" action="{{ $action }}" id="product-form" 
    enctype="multipart/form-data" 
    novalidate>
    @csrf
    @if ($product)
        @method('PUT')
    @endif

    <div class="mt-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product?->name) }}"
            @class(['mt-1 block w-full rounded-md shadow-sm', 'border-gray-300' => ! $errors->has('name'), 'border-red-500' => $errors->has('name')])
            required minlength="3" maxlength="255" autocomplete="off" aria-describedby="name-error">
        <p id="name-error" class="mt-1 text-sm text-red-600 {{ $errors->has('name') ? '' : 'hidden' }}" data-error-for="name" aria-live="polite">{{ $errors->first('name') }}</p>
    </div>

    <div class="mt-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" rows="4"
            @class(['mt-1 block w-full rounded-md shadow-sm', 'border-gray-300' => ! $errors->has('description'), 'border-red-500' => $errors->has('description')])
            required minlength="3" maxlength="1000" aria-describedby="description-error">{{ old('description', $product?->description) }}</textarea>
        <p id="description-error" class="mt-1 text-sm text-red-600 {{ $errors->has('description') ? '' : 'hidden' }}" data-error-for="description" aria-live="polite">{{ $errors->first('description') }}</p>
    </div>

    <div class="mt-4">
        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
        <input type="number" name="price" id="price" value="{{ old('price', $product?->price) }}" inputmode="numeric"
            @class(['mt-1 block w-full rounded-md shadow-sm', 'border-gray-300' => ! $errors->has('price'), 'border-red-500' => $errors->has('price')])
            required min="0" max="2147483647" step="1" aria-describedby="price-error">
        <p id="price-error" class="mt-1 text-sm text-red-600 {{ $errors->has('price') ? '' : 'hidden' }}" data-error-for="price" aria-live="polite">{{ $errors->first('price') }}</p>
    </div>

    <div class="mt-4">
        <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $product?->stock) }}" inputmode="numeric"
            @class(['mt-1 block w-full rounded-md shadow-sm', 'border-gray-300' => ! $errors->has('stock'), 'border-red-500' => $errors->has('stock')])
            required min="0" max="2147483647" step="1" aria-describedby="stock-error">
        <p id="stock-error" class="mt-1 text-sm text-red-600 {{ $errors->has('stock') ? '' : 'hidden' }}" data-error-for="stock" aria-live="polite">{{ $errors->first('stock') }}</p>
    </div>

    <div class="mt-4">
        <label for="product_category_id" class="block text-sm font-medium text-gray-700">Category</label>
        <select name="product_category_id" id="product_category_id"
            @class(['mt-1 block w-full rounded-md shadow-sm', 'border-gray-300' => ! $errors->has('product_category_id'), 'border-red-500' => $errors->has('product_category_id')])
            required aria-describedby="product_category_id-error">
            <option value="" disabled {{ $selectedCategory ? '' : 'selected' }}>Select a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected($selectedCategory == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <p id="product_category_id-error" class="mt-1 text-sm text-red-600 {{ $errors->has('product_category_id') ? '' : 'hidden' }}" data-error-for="product_category_id" aria-live="polite">{{ $errors->first('product_category_id') }}</p>
    </div>

    <div class="mt-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
            @class(['mt-1 block w-full rounded-md shadow-sm', 'border-gray-300' => ! $errors->has('image'), 'border-red-500' => $errors->has('image')])
            @unless ($product) required @endunless aria-describedby="image-help image-error">
        <p id="image-help" class="mt-1 text-xs text-gray-500">
            @if ($product)
                {{ __('Leave empty to keep the current image.') }}
            @endif
            {{ __('JPG, PNG, or WEBP. Max 2 MB and at least 300 × 300 pixels. You can crop the image before uploading.') }}
        </p>
        <p id="image-error" class="mt-1 text-sm text-red-600 {{ $errors->has('image') ? '' : 'hidden' }}" data-error-for="image" aria-live="polite">{{ $errors->first('image') }}</p>

        <div id="crop-section" class="mt-4 hidden">
            <div id="image-cropper" class="mx-auto w-full max-w-md"></div>
            <div class="mt-3 flex flex-wrap gap-2">
                <button type="button" id="crop-image-button" class="rounded bg-blue-500 px-4 py-2 text-white">Crop &amp; Use Image</button>
                <button type="button" id="cancel-crop-button" class="rounded bg-gray-200 px-4 py-2 text-gray-800">Cancel</button>
            </div>
        </div>

        <div id="image-preview-wrapper" @class(['mt-4', 'hidden' => ! $currentImageUrl])>
            <p class="text-sm font-medium text-gray-700">{{ $product ? __('Current image') : __('Preview') }}</p>
            <img id="image-preview" @if ($currentImageUrl) src="{{ $currentImageUrl }}" @endif alt="{{ __('Cropped image preview') }}" class="mt-2 h-40 w-40 rounded-md border border-gray-200 object-cover">
            <button type="button" id="change-image-button" class="mt-2 block text-sm text-indigo-600 hover:text-indigo-900">Change image</button>
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">{{ $submitLabel }}</button>
    </div>
</form>
