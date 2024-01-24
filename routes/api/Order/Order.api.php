<?php

use App\Http\Controllers\Cart\VnPayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\User\UserController;

Route::prefix('order')->group(function () {

    Route::middleware('role:user')->group(function () {
        Route::get('get', [OrderController::class, 'get']);
        Route::post('create', [OrderController::class, 'create']);
        Route::post('confirmVNPayPayment', [OrderController::class, 'vnpayConfirmPayment']);
        Route::delete('cancelOrder', [OrderController::class, 'cancelOrder']);
    });
    Route::middleware('role:user')->group(function () {
    Route::put('updateStatus', [OrderController::class, 'updateStatus']);
    Route::get('getAll', [OrderController::class, 'getAll']);
    });
});
