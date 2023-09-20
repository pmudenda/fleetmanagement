<?php

use App\Http\Controllers\RemindersController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::group(['middleware' => 'auth', 'prefix' => 'reminders'], function () {

    Route::post('list', [RemindersController::class, 'index'])
        ->name('reminder.list');

    // Renewals
    Route::get('renewal/create', [RemindersController::class, 'createRenewalReminder'])
        ->name('reminder.renewal.new');

    Route::post('renewal/save', [RemindersController::class, 'storeRenewalReminder'])
        ->name('reminder.renewal.save');

    // Service
    Route::get('service/create', [RemindersController::class, 'createServiceReminder'])
        ->name('reminder.service.new');

    Route::post('service/save', [RemindersController::class, 'storeServiceReminder'])
        ->name('reminder.service.save');
});
