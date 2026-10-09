{{-- Template Laravel with Bootstrap --}}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon" href="favicon.ico">
        {{-- SEO and Favicon --}}
        <meta name="description" content="@yield('description', 'My E-commerce Site')">
        <meta name="keywords" content="@yield('keywords', 'e-commerce, online shopping, store')">
        <meta name="author" content="@yield('author', 'My E-commerce Site')">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        @stack('styles')
        @stack('css')
        @stack('head')
    </head>
    <body>
        @include('template.navbar')
        @yield('content')
        @include('template.footer')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>