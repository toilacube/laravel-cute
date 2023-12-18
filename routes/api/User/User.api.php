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

    Route::post('makeAddressDefault', [UserController::class, 'makeAddressDefault']);
    Route::delete('deleteAddress/{addressId}', [UserController::class, 'deleteAddress']);
    Route::put('updateAddress', [UserController::class, 'updateAddress']);
  });

  Route::group([
    'middleware' => 'role:admin',
    'prefix' => 'user'
], function ($router) {

    Route::get('getAllUser', [UserController::class, 'getAllUsers']);
});