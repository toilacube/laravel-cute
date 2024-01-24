<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Review\ReviewController;

Route::prefix('review')->group(function () {
    Route::get('getOne', [ReviewController::class, 'getOne']);
    Route::get('getAll', [ReviewController::class, 'getAll']);
    Route::get('getOfProduct', [ReviewController::class, 'getOfProduct']);
});

Route::middleware('role:user')->prefix('review')->group(function () {

    Route::post('add', [ReviewController::class, 'add']);
    Route::post('addItem', [ReviewController::class, 'addItem']);

    Route::put('update', [ReviewController::class, 'update']);
    Route::delete('delete', [ReviewController::class, 'delete']);
});
