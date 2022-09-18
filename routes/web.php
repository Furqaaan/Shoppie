<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CartController;

Route::get('/', [ProductsController::class,"addProducts"]);
Route::post('/store-products', [ProductsController::class,"storeProducts"])->name("storeProducts");
Route::get('/shop', [ProductsController::class,"viewProducts"])->name("viewProducts");
Route::get('/get-products', [ProductsController::class,"getProducts"])->name("getProducts");

Route::get('/get-cart', [CartController::class,"getCart"])->name("getCart");
Route::post('/add-to-cart', [CartController::class,"addToCart"])->name("addToCart");
Route::post('/delete-cart-item', [CartController::class,"deleteCartItem"])->name("deleteCartItem");
