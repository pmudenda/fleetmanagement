<?php

use App\Http\Controllers\eTollCardController;
use App\Http\Controllers\RemindersController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth', 'prefix' => 'reminders'], function () {

    Route::get('e-toll/cards', [eTollCardController::class, 'create'])
        ->name('e-toll.card');

    Route::post('save/e-toll/cards', [eTollCardController::class, 'store'])
        ->name('e-toll.card.save');

    Route::get('e-toll/cards/list', [eTollCardController::class, 'list'])
        ->name('e-toll.card.list');

    Route::get('e-toll/cards/transactions', [eTollCardController::class, 'uploadTransaction'])
        ->name('e-toll.card.transaction');

    Route::post('save/e-toll/cards/transactions', [eTollCardController::class, 'saveTransaction'])
        ->name('e-toll.card.save.transactions');

    Route::get('e-toll/cards/report', [eTollCardController::class, 'report'])
        ->name('e-toll.card.report');
});
