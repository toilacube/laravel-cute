<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductItemController;


Route::middleware('guest')->prefix('productItem')->group(function () {
    Route::get('/{id}', [ProductItemController::class, 'show']);
});

Route::middleware('auth')->prefix('productItem')->group(function () {
    Route::put('updateQtyInStock', [ProductItemController::class, 'updateQtyInStock']);

    Route::post('add', [ProductItemController::class, 'add']);

    Route::delete('delete', [ProductItemController::class, 'delete']);
});