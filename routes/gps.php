<?php

use App\Livewire\VehicleManagement\Gps\Form as GpsForm;
use App\Livewire\VehicleManagement\Gps\Index as GpsIndex;
use App\Livewire\VehicleManagement\Dashboard\Dashboard as GpsDashboard;
use Illuminate\Support\Facades\Route;

Route::name('gps.')
    ->prefix('gps')
    ->middleware(['auth', 'is.active', 'change.password'])
    ->group(function () {
        Route::get('/', GpsIndex::class)->name('index');
        Route::get('/create', GpsForm::class)->name('create');

        // IMEI-based edit
        Route::get('/{imei}/edit', GpsForm::class)->where('imei', '[0-9]+')->name('edit');

        // Dashboard
        Route::get('/dashboard', GpsDashboard::class)->name('dashboard');
    });
