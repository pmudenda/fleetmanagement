<?php

use App\Http\Controllers\AccidentReporting\AccidentRecordingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'is.active', 'change.password']], function () {
    Route::group(['prefix' => 'accident'], function () {

        Route::get('/report', [AccidentRecordingController::class, 'create'])
            ->name('accident.reporting');

        Route::get('/list', [AccidentRecordingController::class, 'list'])
            ->name('accident.list');

        Route::get('/show', [AccidentRecordingController::class, 'show'])
            ->name('accident.show');

        Route::get('/types', [AccidentRecordingController::class, 'getAccidentTypes'])
            ->name('accident.types');

        Route::get('/natures', [AccidentRecordingController::class, 'getAccidentNatures'])
            ->name('accident.natures');

        Route::post('/save/report', [AccidentRecordingController::class, 'store'])
            ->name('accident.store');

        Route::get('/get/accident/reference/', [AccidentRecordingController::class, 'getLatestAccidentReport'])
            ->name('accident.reports.references');

    });
});



