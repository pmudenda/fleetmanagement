<?php

use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\Configurations\ConfigVehicleBrandsController;
use App\Http\Controllers\Configurations\VehicleBodyTypesController;
use App\Http\Controllers\migration\VehicleDataCleaningController;
use App\Http\Controllers\VehicleManagement\VehicleController;
use App\Http\Controllers\VehicleManagement\VehicleModelsController;
use App\Http\Controllers\VehicleManagement\VehicleOnBoardingController;
use App\Models\reference\BatteryModel;
use App\Models\reference\TyreSizesModel;
use Illuminate\Support\Facades\Log;
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


    Route::delete('/vehicle/print-disk', function () {
        return view("");
    })->name('print.vehicle.disk');


    //BATTERY
    Route::get('/references/battery-brands', function () {
        try {

            $data = BatteryModel::get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('battery.get');
    Route::get('/references/tyre-sizes', function () {
        try {
            $data = TyreSizesModel::get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('tyres.get');
});

Route::group(['prefix' => 'vehicle-management'], function () {

    Route::group(['prefix' => 'onboarding'], function () {

        Route::get('/register', [VehicleOnBoardingController::class, 'start'])
            ->name('new.vehicle');

        Route::get('/vehicle-details', [VehicleOnBoardingController::class, 'start'])
            ->name('view.vehicle');

        Route::get('/show-vehicle-details', [VehicleOnBoardingController::class, 'showDetails'])
            ->name('vehicle.show');

        Route::get('/view/vehicle/details', [VehicleOnBoardingController::class, 'show'])
            ->name('view.vehicle.detail');

        Route::post('post-vehicle-assignment', [VehicleOnBoardingController::class, 'store'])
            ->name('vehicle.assignment.detail');

        Route::post('post-vehicle-details', [VehicleOnBoardingController::class, 'storeVehicleHeader'])
            ->name('new.vehicle.header');

        Route::post('post-chassis-details', [VehicleOnBoardingController::class, 'storeChassisDetails'])
            ->name('vehicle.chassis.detail');

        Route::post('post-engine-details', [VehicleOnBoardingController::class, 'storeEngineDetails'])
            ->name('vehicle.engine.detail');

        Route::post('post-costing-details', [VehicleOnBoardingController::class, 'storeCostingDetails'])
            ->name('vehicle.cost.detail');

        Route::post('post-vehicle.accessories', [VehicleOnBoardingController::class, 'storeAccessoryDetails'])
            ->name('vehicle.accessories.save');

        Route::post('post-body-details', [VehicleOnBoardingController::class, 'storeBodyDetails'])
            ->name('vehicle.body.detail');

        Route::get('verify/document-number', [VehicleOnBoardingController::class, "validateVehicleIdentifiers"])
            ->name('document.number.validation');

        Route::get('/resume', [VehicleOnBoardingController::class, 'resume'])
            ->name('resume.onboarding');

    });

    Route::get('vehicle/all/details', [VehicleController::class, 'getAllDetails'])->name('vehicle.details');

    Route::get('vehicle/details', [VehicleController::class, 'getDetails'])->name('requisition.vehicle.details');

    Route::get('articles/fuels', [ProcurementSystemIntegrationController::class, 'fuelTypes'])->name('fuel.types');

    Route::get('/vehicle/list', [VehicleController::class, 'list'])->name('vehicles.list');

    Route::get('/vehicles', [VehicleController::class, 'register'])->name('vehicle.edit');

    Route::get('/accessories', [VehicleController::class, 'accessories'])->name('vehicle.accessories');


    Route::get('/cleanup', [VehicleDataCleaningController::class, 'cleanUpWindow'])->name('vehicle.data.cleanup');

    Route::get('/cleanup/assignation/list', [VehicleDataCleaningController::class, 'cleanUpList'])->name('vehicle.migration.list');

    Route::post('/cleanup/filter', [VehicleDataCleaningController::class, 'filter'])->name('data.migration.filter');
});

