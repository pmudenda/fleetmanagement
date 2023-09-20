<?php

use App\Http\Controllers\DriverManagement\DriverController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth', 'prefix' => 'driver-management'], function () {
    Route::get('driver/driver', [DriverController::class, 'create'])->name('driver.create');

    Route::post('driver/save', [DriverController::class, 'store'])->name('save.driver');

    Route::get('driver/show', [DriverController::class, 'show'])->name('driver.show');

    Route::get('driver/list', [DriverController::class, 'driverList'])->name('driver.list');

    Route::post('driver/find', [DriverController::class, 'findDriver'])
        ->name('driver.search');
});

