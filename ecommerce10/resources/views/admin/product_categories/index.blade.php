<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Categories') }}
            </h2>
            <a href="#!" 
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'add-category-modal')"
                class="bg-green-500 text-white px-4 py-2 rounded">Add New Category</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto w-full">
                        <table data-datatable class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Slug
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Products Count
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Stock
                                    </th>
                                    <th scope="col" data-orderable="false" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($productCategories as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                           <a href="{{ route('products.index', ['product_category_id'=>$category->id]) }}">{{ $category->name }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $category->products_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $category->products_sum_stock }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="#!" x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-category-modal{{ $category->id }}')" 
                                            class="text-yellow-600 hover:text-yellow-900 ml-4">Edit</a>
                                            <form action="{{ route('product-categories.destroy', $category->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @push('scripts')
                                    <x-modal name="edit-category-modal{{ $category->id }}" :show="$errors->productCategoryUpdate->isNotEmpty()" max-width="lg" focusable>
                                        <form method="post" action="{{ route('product-categories.update', $category->id) }}" id="edit-category-form-{{ $category->id }}" class="p-6">
                                            @csrf
                                            @method('PUT')
                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('Edit Category') }}
                                            </h2>
                                            <div class="mt-4">
                                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                                <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required minlength="3" maxlength="255" value="{{ $category->name }}">
                                            </div>
                                            <div class="mt-4">
                                                <button type="submit" onclick="validateEditCategoryForm({{ $category->id }})" class="bg-yellow-500 text-white px-4 py-2 rounded">Update Category</button>
                                            </div>
                                        </form>
                                    </x-modal>
                                    @endpush
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<x-modal name="add-category-modal" :show="$errors->productCategoryStore->isNotEmpty()" max-width="lg" focusable>
    <form method="post" id="add-category-form" action="{{ route('product-categories.store') }}" class="p-6">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Add New Category') }}
        </h2>
        <div class="mt-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required minlength="3" maxlength="255">
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Category</button>
        </div>
    </form>
</x-modal>
<script>
    // add modal validation
    const addCategoryModal = document.querySelector('[name="add-category-modal"]');
    const addCategoryForm = document.querySelector('#add-category-form');
    addCategoryForm.addEventListener('submit', function(event) {
        const nameInput = addCategoryForm.querySelector('#name');
        if (nameInput.value.length < 3 || nameInput.value.length > 255) {
            event.preventDefault();
            alert('Name must be between 3 and 255 characters.');
        }

        // prevent number inside the name input
        if (/\d/.test(nameInput.value)) {
            event.preventDefault();
            alert('Name cannot contain numbers.');
        }
    });

    //edit modal validation
    function validateEditCategoryForm(id) {
        const editCategoryForms = document.querySelector('#edit-category-form-' + id);
        if (!editCategoryForms) return;
        const form = editCategoryForms;
        console.log('Validating edit form for category ID:', id);
        form.addEventListener('submit', function(event) {
            const nameInput = form.querySelector('#name');
            if (nameInput.value.length < 3 || nameInput.value.length > 255) {
                event.preventDefault();
                alert('Name must be between 3 and 255 characters.');
                return;
            }

            if (/\d/.test(nameInput.value)) {
                event.preventDefault();
                alert('Name cannot contain numbers.');
                return;
            }
            return;
        });
    }
</script>
@endpush
</x-app-layout>
