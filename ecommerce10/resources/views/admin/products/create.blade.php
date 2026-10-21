<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('admin.products._form', [
                        'product' => null,
                        'action' => route('products.store'),
                        'submitLabel' => 'Create Product',
                    ])
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/product-form.js')
    @endpush
</x-app-layout>
