<?php

use App\Livewire\Cart;
use App\Livewire\Catalog;
use App\Livewire\Product;
use Illuminate\Support\Facades\Route;


Route::get('/', Catalog::class)
    ->name('home');
Route::get('products/{product}', Product::class)
    ->name('products.show');
Route::get('cart', Cart::class)
    ->name('cart');
Route::get('cart/success', function () {
    return view('cart.success');
})
    ->name('cart.success');
