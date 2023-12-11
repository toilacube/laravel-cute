<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\api\GoogleController;
use App\Http\Controllers\Auth\PasswordResetController;



Route::group([

    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('test', [AuthController::class, 'test']);

    // TODO: Forgot Password
    Route::post('forgotpassword', [PasswordResetController::class, 'forgotPassword']);
    // TODO : Reset Password
    Route::post('resetpassword', [PasswordResetController::class, 'resetpassword']);

    // Google Sign In
    Route::post('/google/get-google-sign-in-url', [GoogleController::class, 'getGoogleSignInUrl']);
    Route::get('/google/callback', [GoogleController::class, 'loginCallback']);
})->middleware('guest');


Route::group([
    'middleware' => 'auth',
    'prefix' => 'auth'
], function ($router) {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('changepassword', [AuthController::class, 'changepassword']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
