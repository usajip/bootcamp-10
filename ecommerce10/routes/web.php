<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('products', function () {
    echo "Products Page";
});

Route::get('cart', function () {
    echo "Cart Page";
});

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