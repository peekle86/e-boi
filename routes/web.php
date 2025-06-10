<?php

use App\Livewire\Catalog;
use App\Livewire\Product;
use Illuminate\Support\Facades\Route;


Route::get('/', Catalog::class)
    ->name('home');
Route::get('products/{product}', Product::class)
    ->name('products.show');
