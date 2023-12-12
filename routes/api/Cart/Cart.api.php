<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Cart\MomoController;
use App\Http\Controllers\Cart\VnPayController;
use App\Http\Controllers\Category\CheckOutController;
use App\Services\Cart\MomoService;

Route::middleware('role:user')->prefix('cart')->group(function () {

    Route::get('/', [CartController::class, 'cart']);

    Route::post('/add', [CartController::class, 'addToCart']);

    Route::put('/updateQty', [CartController::class, 'updateQty']);

    Route::put('/replaceCartItem', [CartController::class, 'replaceCartItem']);

    Route::delete('/removeCartItem', [CartController::class, 'removeCartItem']);

    Route::prefix('checkout')->group(function(){
        Route::post('create-payment', [VnPayController::class, 'vnpay_create_payment']);
        
    });
   
});
