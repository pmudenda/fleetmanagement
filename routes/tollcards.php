<?php

use App\Http\Controllers\ETollCardController;
use App\Http\Controllers\RemindersController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth','is.active','change.password'], 'prefix' => 'reminders'], function () {

    Route::get('e-toll/cards', [ETollCardController::class, 'create'])
        ->name('e-toll.card');

    Route::post('save/e-toll/cards', [ETollCardController::class, 'store'])
        ->name('e-toll.card.save');

    Route::get('e-toll/cards/list', [ETollCardController::class, 'list'])
        ->name('e-toll.card.list');

    Route::get('e-toll/cards/transactions', [ETollCardController::class, 'uploadTransaction'])
        ->name('e-toll.card.transaction');

    Route::post('save/e-toll/cards/transactions', [ETollCardController::class, 'saveTransaction'])
        ->name('e-toll.card.save.transactions');

    Route::get('e-toll/cards/report', [ETollCardController::class, 'report'])
        ->name('e-toll.card.report');
});
