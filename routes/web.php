<?php

use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\Configurations\ChargeOutRateController;
use App\Http\Controllers\Configurations\GeneralTablesController;
use App\Http\Controllers\DriverManagement\DriverController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\Requisitions\FuelRequisitionController;
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use App\Http\Controllers\Workflow\WorkflowController;
use App\Http\Controllers\WorkshopManagement\MaintenanceController;
use App\Http\Controllers\WorkshopManagement\WorkshopController;
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
            Route::post('/editRecord', [GeneralTablesController::class, "editRecord"])->name('edit.data');
            Route::post('/deleteRecord', [GeneralTablesController::class, "deleteRecord"])->name('delete.data');
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

        Route::get('vehicle/charge-outrate', [ChargeOutRateController::class, 'index'])->name('charge.out.rate');
        Route::post('save/charge-outrate', [ChargeOutRateController::class, 'store'])->name('save.charge.out.rate');
    });

    Route::group(['prefix' => 'requisitions'], function () {
        Route::get('/fuel', [FuelRequisitionController::class, 'create'])->name('new.fuel.requisition');
        Route::get('/fuel/approve', [FuelRequisitionController::class, 'show'])->name('show.fuel.requisition');
        Route::post('/fuel/save', [FuelRequisitionController::class, 'store'])->name('save.fuel.requisition');
        Route::get('/fuel-requisitions/list', [FuelRequisitionController::class, 'index'])->name('list.fuel.requisition');
        Route::post('/fuel/odometer/validation', [FuelRequisitionController::class, 'validateOdometer'])->name('fuel.odometer.validation');
        Route::post('/fuel/last/requisition', [FuelRequisitionController::class, 'latestRequisition'])->name('fuel.last.requisition');

        Route::post('/workflow/fuel/approve', [WorkflowController::class, 'processFuelRequisitionApproval'])->name('workflow.approve');
    });

    Route::get('searchProjects', [ProjectsController::class, 'findProjectByCode'])->name('search.project');


    Route::group(['prefix' => 'workshop-management'], function () {

        Route::get('workshops/list', [WorkshopController::class, 'index'])->name('workshop.list');

        Route::get('workshop/section', [WorkshopController::class, 'sections'])->name('workshop.sections');

        Route::get('workshops/list/json', [WorkshopController::class, 'getActiveWorkShops'])->name('all.workshop.list');

        Route::get('fuel-levels/list/json', [MaintenanceController::class, 'getFuelLevels'])->name('fuels.levels');

        /** Job Card Processing **/
        Route::get('requisitions/maintenance', [MaintenanceController::class, 'create'])->name('maintenance.requisition');
        Route::get('requisitions/maintenance/job-card/accessories', [MaintenanceController::class, 'accessoriesTab'])->name('accessories.job.card');
        Route::get('requisitions/maintenance/job-card/defects', [MaintenanceController::class, 'defectsTab'])->name('defects.job.card');

        Route::post('save/job/card', [MaintenanceController::class, 'processJobCard'])->name('process.job_card');
        Route::post('save/job/card/accessories', [MaintenanceController::class, 'processJobCardAccessories'])->name('job_card.accessories.checkin');
        Route::post('save/job-card/defects', [MaintenanceController::class, 'processJobCardDefects'])->name('defects.job_card');
        Route::post('save/workshop/requisition', [MaintenanceController::class, 'processWorkShopRequisition'])->name('process.requisition');
        // supporting
        Route::get('requisitions/maintenance/list', [MaintenanceController::class, 'list'])->name('maintenance.list');
        Route::post('requisitions/maintenance', [MaintenanceController::class, 'create'])->name('save.workshop.requisition');

        //delete defect
        Route::post('/deleteRecord', [MaintenanceController::class, "deleteRecord"])->name('delete.defect.record');
        Route::post('/deleteMaterialRecord', [MaintenanceController::class, "deleteMaterialRecord"])->name('delete.material.record');


        Route::get('/workshop/requisition', function () {
            return "Requisition Will Show Here";
        })->name('show.workshop.requisition');

        // STORES REQUISITIONS
        Route::get('/workshop/approve', [MaintenanceController::class, 'show'])->name('show.workshop.requisition');
        Route::post('/workflow/stores/requisition/approve', [WorkflowController::class, 'processStoresRequisitionApproval'])->name('stores.requisition.approve');


    });

    Route::group(['prefix' => 'driver-management'], function () {
        Route::get('driver/driver', [DriverController::class, 'create'])->name('driver.create');

        Route::post('driver/save', [DriverController::class, 'store'])->name('save.driver');

        Route::get('driver/show', [DriverController::class, 'show'])->name('driver.show');

        Route::get('driver/list', [DriverController::class, 'driverList'])->name('driver.list');

        Route::post('driver/find', [DriverController::class, 'findDriver'])->name('driver.search');
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('fuel/requisitions', function () {
            return view('modules.reports.index');
        })->name('reports.fuel.requisitions');
    });

});

Route::get('load/procurement/articles', [MaintenanceController::class, 'searchArticle'])
    ->name('load.articles');

Route::get('load/article/details', [ProcurementSystemIntegrationController::class, "getArticleDetails"])
    ->name('load.article.details');

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

Route::get('parts-selection', function (Request $request) {

    $step = '1';
    $repairTypes = [];
    $accessories_checked_in = [];
    $accessories = [];
    $details = [];
    $workshop_sections = [];
    $defects = [];
    $comments = [];

    $view_name = 'modules.requisitions.maintenance.create_old';

    return view($view_name)->with(
        compact(
            'repairTypes',
            'accessories',
            'details',
            'accessories_checked_in',
            'step',
            'workshop_sections',
            'defects',
            'comments'
        )
    );
})->name('parts.selection');


