<?php

use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth','is.active','change.password'], 'prefix' => 'reports'], function () {

    Route::get('fuel/cost', \App\Livewire\Reports\Fuel\FuelIndex::class)
        ->name('reports.fuel.requisitions');

    Route::get('data/fuel/cost', [ReportsController::class, 'getFuelCost'])->name('reports.fuel.data');

    Route::get('vehicle/status', [ReportsController::class, 'vehicleByStatus'])
        ->name('reports.vehicle.status');
});
