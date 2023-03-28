<?php

use App\Http\Controllers\VehicleManagement\VehicleOnBoardingController;
use App\Models\vehiclemanagement\VehicleHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//, 'middleware' => 'auth'
Route::group(['prefix' => 'vehicle-management'], function () {

    Route::get('/register', function () {
        return view('vehicleManagement.register.index');
    })->name('new.vehicle');


    Route::get('/vehicle/list', function () {
        $vehicleList = VehicleHeader::get();
        return view('vehicleManagement.vehicleList')
            ->with(compact('vehicleList'));
    })->name('vehicles.list');


    Route::get('/vehicles', function (Request $request) {

        return view('vehicleManagement.vehicleList');
    })->name('vehicle.edit');

    /*VEHICLES*/
    Route::post('vehicles', VehicleOnBoardingController::class)->name('api.vehicle.new');


    Route::get('/insurancelist', function () {
        return view('VehicleManagement.insurancelist');
    })->name('insurancelist');

    Route::get('/legaldocumentlist', function () {
        return view('VehicleManagement.insurancelist');
    })->name('legaldocumentlist');


});
