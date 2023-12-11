<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::group([
    'middleware' => 'role:user',
    'prefix' => 'user'
], function ($router) {

    Route::get('/', [UserController::class, 'user']);
    Route::put('updateInfo', [UserController::class, 'updateInfo']);
    Route::get('getAddresses', [UserController::class, 'getAddresses']);
    Route::post('addAddress', [UserController::class, 'addAddress']);
    Route::post('MakeAAddress', [UserController::class, 'updateAddress']);
    Route::delete('delddressDefault', [UserController::class, 'MakeAddressDefault']);
    Route::put('updateeteAddress/{addressId}', [UserController::class, 'deleteAddress']);
  });