<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;



Route::group([

    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('test', [AuthController::class, 'test']);
    Route::post('forgotpassword', [PasswordResetController::class, 'forgotPassword'])->name('password.request');
});



Route::group([
    'middleware' => 'auth',
    'prefix' => 'auth'
], function ($router) {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('changepassword', [AuthController::class, 'changepassword']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('resetpassword', [PasswordResetController::class, 'resetpassword'])->name('password.reset');
});
