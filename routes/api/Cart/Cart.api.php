<?php

use App\Http\Controllers\Cart\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('cart')->group(function () {

    Route::get('/', [CartController::class, 'cart']);

    Route::post('/add', [CartController::class, 'addToCart']);

    Route::put('/updateQty', [CartController::class, 'updateQty']);

    Route::put('/replaceCartItem', [CartController::class, 'replaceCartItem']);

    Route::delete('/removeCartItem', [CartController::class, 'removeCartItem']);

});
