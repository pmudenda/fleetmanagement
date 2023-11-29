<?php


use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {

    Route::get('/index', function () {
        return view('VehicleManagement.index');
    })->name('settings');
});
