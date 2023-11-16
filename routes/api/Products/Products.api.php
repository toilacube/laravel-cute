<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductsController;


Route::get('/casual', [ProductsController::class, 'casual']);
Route::get('/test', [ProductsController::class, 'test']);
