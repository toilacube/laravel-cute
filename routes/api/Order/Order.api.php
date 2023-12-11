<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Order\OrderController;



Route::middleware('role:user')->prefix('order')->group(function () {

    Route::get('get', [OrderController::class, 'get']);
    // TODO: khong hieu api viet gi ca
    Route::post('create', [OrderController::class, 'create']);
});


Route::middleware('role:admin')->prefix('order')->group(function () {
    Route::put('updateStatus', [OrderController::class, 'updateStatus']);
    Route::get('getAll', [OrderController::class, 'getAll']);
});
