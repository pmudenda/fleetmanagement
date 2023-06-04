<?php

use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\Configurations\ChargeOutRateController;
use App\Http\Controllers\Configurations\GeneralTablesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\Requisitions\FuelRequisitionController;
use App\Http\Controllers\Security\PasswordResetController;
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\DriverManagement\DriverController;
use App\Http\Controllers\VehicleManagement\VehicleController;
use App\Http\Controllers\VehicleManagement\VehicleOnBoardingController;
use App\Http\Controllers\Workflow\WorkflowController;
use App\Http\Controllers\WorkshopManagement\MaintenanceController;
use App\Http\Controllers\WorkshopManagement\WorkshopController;
use App\Models\VehicleManagement\VehicleHeader;
use App\Services\VehicleManagement\OnBoarding\OnBoardingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});


Route::post('logout', [HomeController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {

    //SESSION EXPIRE
    Route::post('getStatus', function () {
        $user = Auth()->user();
        if (!$user || $user->id == 0) {
            return response()->json(array(
                'message' => 'Session Expired',
                'state' => 'expired'
            ));
        }
        return response()->json(array(
            'message' => '',
            'state' => 'active',
        ));
    })->name('session.status');

    Route::get('/home', [HomeController::class, 'dashboard'])->name('home');

    Route::group(['prefix' => 'user-management'], function () {

        Route::get('user/profile', [UsersController::class, 'profile'])->name('profile');

        Route::get('users/new', [UsersController::class, 'create'])->name('users.new');

        Route::get('users/list', [UsersController::class, 'index'])->name('users.list');

        Route::post('users/resetPassword', [PasswordResetController::class, 'resetPassword'])->name('user.reset.password');

        // user.store
        Route::resource('/user', UsersController::class);

        Route::post('/get-employee-data', [UsersController::class, 'search'])->name('user.search');
        Route::post('user/attach/{id}', [UsersController::class, 'attach'])->name('user.attach');
        Route::post('user/detach/{id}', [UsersController::class, 'detach'])->name('user.detach');
    });

    Route::group(['prefix' => 'security'], function () {

        /************ roles************/
        Route::post('/roles/attach/{id}', [RolesController::class, 'attach'])->name('roles.attach');
        Route::post('/roles/detach/{id}', [RolesController::class, 'detach'])->name('roles.detach');

        Route::resource('roles', RolesController::class);

        /************ permission ************/
        Route::resource('permissions', PermissionsController::class);
    });

    Route::group(['prefix' => 'system-configuration'], function () {

        /** GENERAL TABLES */
        Route::group(['prefix' => 'general'], function () {
            Route::get('/open-view', [GeneralTablesController::class, "openFormTypeView"])->name('configuration.general.table');
            Route::get('/types', [GeneralTablesController::class, "show"]);
            Route::post('/general_tables', [GeneralTablesController::class, "save"])->name('save.data');
            Route::put('/edit/{id}', [GeneralTablesController::class, "edit"])->name('edit.data');
            Route::delete('/delete/{id}', [GeneralTablesController::class, "delete"])->name('delete.data');
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

        Route::get('vehicle/fuel-allocation', function () {
            return view('modules.configurations.fuelallocation');
        })->name('vehicle.fuel.allocation');

        Route::get('vehicle/charge-outrate',[ChargeOutRateController::class, 'index'])->name('charge.out.rate');
        Route::post('save/charge-outrate',[ChargeOutRateController::class, 'store'])->name('save.charge.out.rate');
    });

    Route::group(['prefix' => 'requisitions'], function () {
        Route::get('/fuel', [FuelRequisitionController::class, 'create'])->name('new.fuel.requisition');
        Route::get('/fuel/approve', [FuelRequisitionController::class, 'show'])->name('show.fuel.requisition');
        Route::post('/fuel/save', [FuelRequisitionController::class, 'store'])->name('save.fuel.requisition');
        Route::get('/fuel/list', [FuelRequisitionController::class, 'index'])->name('list.fuel.requisition');
        Route::post('/fuel/odometer/validation', [FuelRequisitionController::class, 'validateOdometer'])->name('fuel.odometer.validation');
        Route::post('/fuel/last/requisition', [FuelRequisitionController::class, 'latestRequisition'])->name('fuel.last.requisition');

        Route::post('/workflow/fuel/approve', [WorkflowController::class, 'processFuelRequisitionApproval'])->name('workflow.approve');
    });

    Route::get('searchProjects', [ProjectsController::class, 'findProjectByCode'])->name('search.project');

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

        Route::get('/cleanup', [VehicleController::class, 'cleanUpWindow'])->name('vehicle.data.cleanup');

        Route::get('/accessories', [VehicleController::class, 'accessories'])->name('vehicle.accessories');

        Route::get('/cleanup/assignation/list', [VehicleController::class, 'cleanUpList'])->name('vehicle.migration.list');
    });

    Route::group(['prefix' => 'workshop-management'], function () {

        Route::get('workshops/list', [WorkshopController::class, 'index'])->name('workshop.list');

        Route::get('workshop/section',[WorkshopController::class, 'sections'])->name('workshop.sections');

        Route::get('workshops/list/json', [WorkshopController::class, 'getActiveWorkShops'])->name('all.workshop.list');

        Route::get('fuel-levels/list/json', [MaintenanceController::class, 'getFuelLevels'])->name('fuels.levels');

        /** Job Card Processing **/
        Route::get('requisitions/maintenance', [MaintenanceController::class, 'create'])->name('maintenance.requisition');
        Route::get('requisitions/maintenance/job-card/accessories', [MaintenanceController::class, 'step2'])->name('accessories.job.card');
        Route::get('requisitions/maintenance/job-card/defects', [MaintenanceController::class, 'step3'])->name('defects.job.card');



        Route::post('save/job/card', [MaintenanceController::class, 'processJobCard'])->name('process.job_card');
        Route::post('save/job/card/accessories', [MaintenanceController::class, 'processJobCardAccessories'])->name('job_card.accessories.checkin');
        Route::get('save/job-card/defects', [MaintenanceController::class, 'processJobCardDefects'])->name('defects.job_card');
        // supporting
        Route::get('requisitions/maintenance/list', [MaintenanceController::class, 'list'])->name('maintenance.list');

        Route::post('requisitions/maintenance', [MaintenanceController::class, 'create'])->name('save.workshop.requisition');
    });

    Route::group(['prefix' => 'driver-management'], function () {
        Route::get('driver/driver', [DriverController::class, 'create'])->name('driver.create');

        Route::post('driver/save', [DriverController::class, 'store'])->name('save.driver');

        Route::get('driver/show', [DriverController::class, 'show'])->name('driver.show');

        Route::get('driver/list', [DriverController::class, 'driverList'])->name('driver.list');

        Route::post('driver/find', [DriverController::class, 'findDriver'])->name('driver.search');
    });


});

Route::get('barcodes', function (Request $request) {

   /* if (!$request->has('data')) {
        return "No Data Supplied";
    }
    $service = new OnBoardingService();
    $barCodeImagePath = $service->generateBarCode(
        new VehicleHeader(
            ['registration_number' => $request->get('data')]
        )
    );
    return '<img alt="testing" src="' . asset('storage/' . $barCodeImagePath) . '"/>';*/
})->name('barcode.generate');
