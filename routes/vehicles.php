<?php

use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\Configurations\ConfigVehicleBrandsController;
use App\Http\Controllers\Configurations\VehicleBodyTypesController;
use App\Http\Controllers\migration\VehicleDataCleaningController;
use App\Http\Controllers\VehicleManagement\MeterEntryController;
use App\Http\Controllers\VehicleManagement\VehicleController;
use App\Http\Controllers\VehicleManagement\VehicleModelsController;
use App\Http\Controllers\VehicleManagement\VehicleOnBoardingController;
use App\Models\Reference\GtaVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth', 'prefix' => 'v1/en'], function (): void {

    Route::group(['prefix' => 'vehicle/brands', 'as' => 'brands.'], function () {
        Route::get('', [ConfigVehicleBrandsController::class, 'get'])->name('get');
        Route::post('', [ConfigVehicleBrandsController::class, 'store'])->name('save');
        Route::delete('', [ConfigVehicleBrandsController::class, 'destroy'])->name('delete');
    });


    Route::resource('vehicle/models', VehicleModelsController::class, [
        'names' => [
            'get' => 'models.get',
            'store' => 'models.save',
            'destroy' => 'models.delete',
        ]
    ]);

    /** BODY TYPES **/
    Route::resource('vehicle/body-types', VehicleBodyTypesController::class, [
        'names' => [
            'get' => 'body_type.get',
            'store' => 'body_type.save',
            'destroy' => 'body_type.delete',
        ]
    ]);

    Route::delete('/vehicle/print-disk', function () {
        return view("");
    })->name('print.vehicle.disk');


    //BATTERY
    Route::get('/references/battery-brands', [ProcurementSystemIntegrationController::class, 'getBatterySizes'])
        ->name('battery.get');

    Route::get('/references/tyre-sizes', [ProcurementSystemIntegrationController::class, 'getTyreSizes'])
        ->name('tyres.get');
});

Route::group(['middleware' => 'auth', 'prefix' => 'vehicle-management'], function () {

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

    Route::get('vehicle/all/details', [VehicleController::class, 'getAllDetails'])
        ->name('vehicle.details');

    Route::get('requisitions/vehicle/details', [VehicleController::class, 'getVehicleDetailsByRegistration'])
        ->name('requisition.vehicle.details');

    Route::get('articles/fuels', [ProcurementSystemIntegrationController::class, 'fuelTypes'])
        ->name('fuel.types');

    Route::get('/vehicle/list', [VehicleController::class, 'list'])->name('vehicles.list');

    Route::get('/vehicles', [VehicleController::class, 'register'])->name('vehicle.edit');

    Route::get('/accessories', [VehicleController::class, 'accessories'])
        ->name('vehicle.accessories');

    Route::post('/save/clean/data', [VehicleDataCleaningController::class, 'saveData'])
        ->name('save.clean.data');

    Route::post('/cleanup/filter', [VehicleDataCleaningController::class, 'filter'])
        ->name('data.migration.filter');

    Route::post('find/vehicle', function (Request $request) {
        try {
            $vehicle = GtaVehicle::where('matricula', $request->get('reg_num'))->get();
            return response()->json([
                'success' => !empty($vehicle),
                'payload' => $vehicle
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => 'We could not complete processing your request, please try again later'
            ]);
        }
    })->name('cleanup.vehicle.find');

    Route::get('odometer/logs/new', [MeterEntryController::class, 'create'])
        ->name('new.fleet.movement');
    Route::post('odometer/logs/new', [MeterEntryController::class, 'store'])
        ->name('save.odometer.log');

    Route::get('odometer/log/vehicle/details', [VehicleController::class, 'getVehicleDetails'])
        ->name('odometer.log.vehicle.details');

});



