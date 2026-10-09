<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Layout Component' }}</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="favicon.ico">
    {{-- SEO and Favicon --}}
    <meta name="description" content="{{ $description ?? 'My E-commerce Site' }}">
    <meta name="keywords" content="{{ $keywords ?? 'e-commerce, online shopping, store' }}">
    <meta name="author" content="{{ $author ?? 'My E-commerce Site' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @include('template.navbar')
    {{ $slot }}
    @include('template.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    @vite(['resources/js/scripts.js'])
    @stack('scripts')
</body>
</html>