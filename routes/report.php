<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth', 'prefix' => 'reports'], function () {

    Route::get('fuel/cost', [ReportsController::class, 'fuelCost'])
        ->name('reports.fuel.requisitions');

    Route::get('data/fuel/cost', [ReportsController::class, 'getFuelCost'])->name('reports.fuel.data');

    Route::get('vehicle/status', [ReportsController::class, 'vehicleByStatus'])
        ->name('reports.vehicle.status');
});
