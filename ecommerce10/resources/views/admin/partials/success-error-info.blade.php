@if (session('success'))
    <div class="mx-6 mt-6 rounded-md bg-green-50 p-4 text-sm text-green-700" role="status">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mx-6 mt-6 rounded-md bg-red-50 p-4 text-sm text-red-700" role="status">
        {{ session('error') }}
    </div>
@endif
@if(session('errors') && is_array(session('errors')))
    <div class="mx-6 mt-6 rounded-md bg-red-50 p-4 text-sm text-red-700" role="status">
        <ul>
            @foreach(session('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('info'))
    <div class="mx-6 mt-6 rounded-md bg-blue-50 p-4 text-sm text-blue-700" role="status">
        {{ session('info') }}
    </div>
@endif