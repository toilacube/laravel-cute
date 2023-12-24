<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductItemController;


Route::prefix('productItem')->group(function () {
    Route::get('/{id}', [ProductItemController::class, 'show']);
    Route::get('/', [ProductItemController::class, 'index']);
});

Route::middleware('role:admin')->prefix('productItem')->group(function () {
    Route::put('updateQtyInStock', [ProductItemController::class, 'updateQtyInStock']);
    Route::put('updateQtyOfListItem', [ProductItemController::class, 'updateQtyOfListItem']);
    Route::post('add', [ProductItemController::class, 'add']);

    Route::delete('delete', [ProductItemController::class, 'delete']);
});
