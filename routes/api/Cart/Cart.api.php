<?php

use App\Http\Controllers\Cart\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/cart", [CartController::class,'index']);