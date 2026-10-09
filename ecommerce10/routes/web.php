<?php
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/home2', [App\Http\Controllers\HomeController::class, 'index2']);
Route::get('/home-layout/{id}', [App\Http\Controllers\HomeController::class, 'indexLayoutComponent'])->name('home.layout');

Route::get('products', [App\Http\Controllers\ProductController::class, 'index']);

Route::get('cart', [App\Http\Controllers\CartController::class, 'index']);

Route::get('checkout', function () {
    echo "Checkout Page";
});

Route::get('/about-dksjhdaksjhkd', function () {
    echo "About Page";
});

Route::get('greeting-user/{name}/age/{age}', function ($name, $age) {
    return "Hello, " . $name . ". You are " . $age . " years old.";
});

Route::middleware('throttle:5,1')->group(function () {
    Route::get('/profile', function () {
        echo "Profile Page";
    });
});