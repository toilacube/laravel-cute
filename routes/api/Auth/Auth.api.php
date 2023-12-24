<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\api\GoogleController;
use App\Http\Controllers\Auth\PasswordResetController;



Route::group([

    'prefix' => 'auth',

], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::post('forgotpassword', [PasswordResetController::class, 'forgotPassword']);
    Route::post('resetpassword', [PasswordResetController::class, 'resetpassword']);


    Route::get('google-login', [GoogleController::class, 'getGoogleSignInUrl']);
    Route::get('/google/callback', [GoogleController::class, 'loginCallback']);
});


Route::group([
    'middleware' => 'role:user',
    'prefix' => 'auth'
], function ($router) {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('changepassword', [AuthController::class, 'changepassword']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
