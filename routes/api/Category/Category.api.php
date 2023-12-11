<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\CategoryController;





Route::middleware('auth')->prefix('category')->group(function () {

    Route::post('add', [CategoryController::class, 'add']);

    Route::put('update', [CategoryController::class, 'update']);

    Route::delete('delete', [CategoryController::class, 'delete']);
});


Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
});
