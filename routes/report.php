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

    Route::get('fuel/status', \App\Livewire\Reports\Fuel\Status\FuelStatusIndex::class)
        ->name('reports.fuel.status');

    Route::get('fuel', \App\Livewire\Reports\Fuel\FuelPeriodReport::class)
        ->name('reports.fuel');

    Route::get('spares', \App\Livewire\Reports\Sprares\SparesPeriodReport::class)
        ->name('reports.spares');

    Route::get('data/fuel/cost', [ReportsController::class, 'getFuelCost'])->name('reports.fuel.data');

    Route::get('vehicle/status', [ReportsController::class, 'vehicleByStatus'])
        ->name('reports.vehicle.status');

    Route::get('overdue-in-workshop', \App\Livewire\Reports\Workshop\OverdueInWorkshopReport::class)
        ->name('reports.workshop.overdue_in_workshop');
});
