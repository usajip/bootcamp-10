<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/search', [App\Http\Controllers\HomeController::class, 'search']);

// Route::get('products', [App\Http\Controllers\ProductController::class, 'index']);

Route::get('cart', [App\Http\Controllers\CartController::class, 'index']);

Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::resource('product-categories', App\Http\Controllers\ProductCategoryController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
