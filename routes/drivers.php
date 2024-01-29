<?php

use App\Http\Controllers\DriverManagement\DriverController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth','is.active','change.password'], 'prefix' => 'driver-management'], function () {
    Route::get('driver/driver', [DriverController::class, 'create'])->name('driver.create');

    Route::post('driver/save', [DriverController::class, 'store'])->name('save.driver');

    Route::post('driver/{driver}/update', [DriverController::class, 'update'])->name('update.driver');


    Route::get('driver/{user}/show', [DriverController::class, 'show'])->name('driver.show');

    Route::get('driver/list', [DriverController::class, 'driverList'])->name('driver.list');

    Route::post('driver/find', [DriverController::class, 'findDriver'])
        ->name('driver.search');
});

