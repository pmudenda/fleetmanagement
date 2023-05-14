<?php

use App\Http\Controllers\Configurations\ConfigVehicleBrandsController;
use App\Http\Controllers\Configurations\VehicleBodyTypesController;
use App\Http\Controllers\VehicleManagement\VehicleModelsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/en'], function (): void {
    Route::prefix('brands')->middleware(['api'])->group(static function (): void {
        Route::get('/brands', [ConfigVehicleBrandsController::class, 'index'])->name('brands.get');
        Route::post('/store', [ConfigVehicleBrandsController::class, 'store'])->name('brands.save');
        Route::delete('/', [ConfigVehicleBrandsController::class, 'destroy'])->name('brands.delete');
    });

    /** MODELS **/
    Route::post('/models', [VehicleModelsController::class, 'store'])->name('models.save');
    Route::get('/models', [VehicleModelsController::class, 'get'])->name('models.get');
    Route::delete('/models', [VehicleModelsController::class, 'destroy'])->name('models.delete');


    /** BODY TYPES **/
    Route::post('/vehicle/body-types', [VehicleBodyTypesController::class, 'store'])->name('body_type.save');
    Route::get('/vehicle/body-types', [VehicleBodyTypesController::class, 'index'])->name('body_type.get');
    Route::delete('/vehicle/body-types', [VehicleBodyTypesController::class, 'destroy'])->name('body_type.delete');
});
