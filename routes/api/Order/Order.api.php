<?php

use App\Http\Controllers\Cart\VnPayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Order\OrderController;



Route::middleware('role:user')->prefix('order')->group(function () {

    Route::get('get', [OrderController::class, 'get']);
    Route::post('create', [OrderController::class, 'create']);
    Route::post('confirm', [VnPayController::class, 'confirmPayment']);
});


Route::middleware('guest')->prefix('order')->group(function () {
    Route::put('updateStatus', [OrderController::class, 'updateStatus']);
    Route::get('getAll', [OrderController::class, 'getAll']);
});