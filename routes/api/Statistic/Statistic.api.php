<?php

use App\Http\Controllers\StatisticController\StatisticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('role:admin')->prefix('statistic')->group(function () {
    Route::get('bestsellers', [StatisticController::class, 'bestSellers']);
    Route::get('lifetimesales', [StatisticController::class, 'lifetimeSales']);
    Route::get('salestatistic', [StatisticController::class, 'saleStatistic']);
});
