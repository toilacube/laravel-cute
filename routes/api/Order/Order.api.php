<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Order\OrderController;


Route::middleware('auth')->prefix('order')->group(function () {

Route::get('get', [OrderController::class, 'get']);

Route::get('getAll', [OrderController::class, 'getAll']);

Route::put('updateStatus', [OrderController::class, 'updateStatus']);
// TODO 
Route::post('create', [OrderController::class, 'create']);
});
