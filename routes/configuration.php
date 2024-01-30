<?php

use App\Http\Controllers\Configurations\ChargeOutRateController;
use App\Http\Controllers\Configurations\GeneralTablesController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth', 'is.active', 'change.password'],
    'prefix' => 'system-configuration'], function () {

    /** GENERAL TABLES */
    Route::group(['prefix' => 'general'], function () {
        Route::get('/open-view', [GeneralTablesController::class, "openFormTypeView"])
            ->name('configuration.general.table');
        Route::get('/types', [GeneralTablesController::class, "show"]);
        Route::post('/general_tables', [GeneralTablesController::class, "save"])
            ->name('save.data');
        Route::post('/editRecord', [GeneralTablesController::class, "editRecord"])
            ->name('edit.data');
        Route::post('/deleteRecord', [GeneralTablesController::class, "deleteRecord"])
            ->name('delete.data');


        Route::prefix('town')->name('general.town.')->group(function () {
            Route::get('/', \App\Livewire\Config\Town\TownIndex::class)->name('index');
            Route::get('/create', \App\Livewire\Config\Town\TownCreate::class)->name('create');
            Route::get('/{town}/edit', \App\Livewire\Config\Town\TownEdit::class)->name('edit');

            Route::prefix('{town}/distance')->name('distance.')->group(function () {
                Route::get('/', \App\Livewire\Config\Town\Distanceindex::class)->name('index');
                Route::get('/create', \App\Livewire\Config\Town\DistanceCreate::class)->name('create');
                Route::get('/{distance}/edit', \App\Livewire\Config\Town\DistanceEdit::class)->name('edit');

            });
        });
    });

    Route::get('vehicle/make', function () {
        return view('modules.configurations.vehicle.brands');
    })->name('vehicle.make');

    Route::get('vehicle/models', function () {
        return view('modules.configurations.vehicle.models');
    })->name('vehicle.models');


    Route::get('vehicle/body-types', function () {
        return view('modules.configurations.vehicle.types');
    })->name('vehicle.body.types');


    Route::get('vehicle/charge-out-rate', [ChargeOutRateController::class, 'create'])
        ->name('charge.out.rate');

    Route::post('save/charge-outrate', [ChargeOutRateController::class, 'store'])
        ->name('save.charge.out.rate');
});
