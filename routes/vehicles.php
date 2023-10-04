<?php

use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\Configurations\ConfigVehicleBrandsController;
use App\Http\Controllers\Configurations\VehicleBodyTypesController;
use App\Http\Controllers\FuelAllocationController;
use App\Http\Controllers\VehicleManagement\InsuranceController;
use App\Http\Controllers\VehicleManagement\MeterEntryController;
use App\Http\Controllers\VehicleManagement\TomCardManagementController;
use App\Http\Controllers\VehicleManagement\VehicleController;
use App\Http\Controllers\VehicleManagement\VehicleModelsController;
use App\Http\Controllers\VehicleManagement\VehicleOnBoardingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'is.active', 'change.password'], 'prefix' => 'v1/en'], function (): void {

    Route::group(['prefix' => 'vehicle/brands', 'as' => 'brands.'], function () {
        Route::get('', [ConfigVehicleBrandsController::class, 'get'])->name('get');
        Route::post('', [ConfigVehicleBrandsController::class, 'store'])->name('save');
        Route::delete('', [ConfigVehicleBrandsController::class, 'destroy'])->name('delete');
    });


    Route::group(['prefix' => 'vehicle/models', 'as' => 'models.'], function () {
        Route::get('', [VehicleModelsController::class, 'get'])->name('get');
        Route::post('', [VehicleModelsController::class, 'store'])->name('save');
        Route::delete('', [VehicleModelsController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'vehicle/body-types', 'as' => 'body_type.'], function () {
        Route::get('', [VehicleBodyTypesController::class, 'get'])->name('get');
        Route::post('', [VehicleBodyTypesController::class, 'store'])->name('save');
        Route::delete('', [VehicleBodyTypesController::class, 'destroy'])->name('delete');
    });


    Route::delete('/vehicle/print-disk', function () {
        return view("");
    })->name('print.vehicle.disk');


    //BATTERY
    Route::get('/references/battery-brands', [ProcurementSystemIntegrationController::class, 'getBatterySizes'])
        ->name('battery.get');

    Route::get('/references/tyre-sizes', [ProcurementSystemIntegrationController::class, 'getTyreSizes'])
        ->name('tyres.get');
});

Route::group(['middleware' => ['auth', 'is.active', 'change.password'], 'prefix' => 'vehicle-management'], function () {

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

    Route::get('vehicle/all/details', [VehicleController::class, 'getVehicleOverViewDetails'])
        ->name('vehicle.details');

    Route::get('vehicle/report', [VehicleController::class, 'getVehicleReportsOverView'])
        ->name('vehicle.details.report');

    Route::get('requisitions/vehicle/details', [VehicleController::class, 'getVehicleDetailsByRegistration'])
        ->name('requisition.vehicle.details');

    Route::get('articles/fuels', [ProcurementSystemIntegrationController::class, 'fuelTypes'])
        ->name('fuel.types');

    Route::get('/vehicle/list', [VehicleController::class, 'list'])->name('vehicles.list');

    Route::get('/vehicle/list/json', [VehicleController::class, 'record'])->name('vehicles.records.list');

    Route::group(['prefix' => 'vehicle/fuel-allocation',
        'as' => 'vehicle.fuel.allocation.'], function () {
        Route::get('create', [FuelAllocationController::class, 'create'])->name('create');
        Route::get('save', [FuelAllocationController::class, 'store'])->name('save');
    });


    Route::group(['prefix' => 'insurance',
        'as' => 'insurance.'],
        function () {

            Route::get('create', [InsuranceController::class, 'create'])->name('create');

            Route::post('save', function () {
                /*try {
                    DB::beginTransaction();

                    $allocation = TomCardAllocation::where('id',
                        '=',
                        $request->get('record'))->first();
                    if (empty($allocation)) {
                        throw new DataNotFoundException("Allocation Record Not Found");
                    }

                    $comments = $request->get('justification');
                    $vehicleRegistration = $allocation->reg_no;

                    $allocation->status = StatusHelper::inactive();
                    $allocation->revocation_justification = $comments;
                    $allocation->date_revoked = Carbon::now();
                    $allocation->revoked_by = Auth::user()->staff_no;
                    $allocation->save();
                    DB::table('vm_vehicle_header')
                        ->where('registration_number',
                            '=',
                            $vehicleRegistration)
                        ->update(['has_tom_card' => 'N']);
                    DB::commit();
                    return response()->json([
                        'state' => 'success',
                        'message' => SystemMessages::TOM_CARD_REVOKED
                    ]);
                } catch (\Exception $e) {
                    $message = SystemMessages::TOM_CARD_REVOCATION_FAILED;
                    if ($e instanceof DataNotFoundException) {
                        $message = $e->getMessage();
                    }
                    Log::error($e);
                    return response()->json([
                        'state' => 'failure',
                        'message' => $message
                    ]);
                }*/
            })->name('save');

        });

    Route::get('/accessories', [VehicleController::class, 'accessories'])
        ->name('vehicle.accessories');

    Route::get('odometer/logs/new', [MeterEntryController::class, 'create'])
        ->name('new.fleet.movement');

    Route::post('odometer/logs/new', [MeterEntryController::class, 'store'])
        ->name('save.odometer.log');

    Route::get('odometer/log/vehicle/details', [VehicleController::class, 'getVehicleDetails'])
        ->name('odometer.log.vehicle.details');

    Route::get('tom/card/assignment', [TomCardManagementController::class, 'create'])
        ->name('assign.tom.card');

    Route::post('tom/card/assignment/save', [TomCardManagementController::class, 'store'])
        ->name('save.assign.tom.card');

    Route::post('tom/card/assignment/revoke', [TomCardManagementController::class, 'revoke'])
        ->name('revoke.assign.tom.card');
});



