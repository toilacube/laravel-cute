<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductsController;

/*
    TODO - add new product (with multi images)

   
*/

Route::middleware('guest')->prefix('product')->group(function () {
    Route::get('/search', [ProductsController::class, 'search']);
    Route::get('/{category_slug}', [ProductsController::class, 'products']);
    Route::get('/get/{id}', [ProductsController::class, 'show']);
});

Route::middleware('auth')->prefix('product')->group(function () {
    Route::post('/add', [ProductsController::class, 'add']);
    Route::put('/update', [ProductsController::class, 'update']);
});