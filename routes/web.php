<?php

use App\Http\Controllers\AccidentReporting\VehicleRecording;
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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    Route::get('/vehiclerecording', [VehicleRecording::class, 'index'])->name('accident.reporting');

    Route::get('/accidenttypes', [VehicleRecording::class, 'accidentTypes'])->name('accident.types');

    Route::get('/accidentnatures', [VehicleRecording::class, 'accidentNatures'])->name('accident.natures');

    Route::post('/accidentrecording', [VehicleRecording::class, 'store'])->name('accident.store');

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
        Route::group(['prefix' => 'maintenance'], function () {

            Route::get('jobCard', [MaintenanceController::class, 'create'])->name('jobCard.requisition');

            Route::get('jobCard/job-card/accessories', [MaintenanceController::class, 'accessoriesTab'])->name('accessories.job.card');

            Route::get('jobCard/job-card/defects', [MaintenanceController::class, 'defectsTab'])->name('defects.job.card');

            Route::get('vehicle-in-workshop', [MaintenanceController::class, 'exitWorkShop'])->name('exit.from.card');

        });

        Route::post('save/job/card', [MaintenanceController::class, 'processJobCard'])->name('process.job_card');

        Route::post('save/job/card/accessories', [MaintenanceController::class, 'processJobCardAccessories'])->name('job_card.accessories.checkin');

        Route::post('save/job-card/defects', [MaintenanceController::class, 'processJobCardDefects'])->name('defects.job_card');

        Route::post('save/workshop/requisition', [MaintenanceController::class, 'processWorkShopMaterials'])->name('process.requisition');

        Route::post('save/workshop/services/requisition', [MaintenanceController::class, 'processWorkShopServices'])->name('process.service.requisition');

        // supporting
        Route::get('requisitions/jobCard/list', [MaintenanceController::class, 'list'])->name('jobCard.list');

        Route::post('requisitions/jobCard', [MaintenanceController::class, 'create'])->name('save.workshop.requisition');

        //delete defect
        Route::post('/deleteRecord', [MaintenanceController::class, "deleteRecord"])->name('delete.defect.record');

        Route::post('/deleteMaterialRecord', [MaintenanceController::class, "deleteMaterialRecord"])->name('delete.material.record');


        Route::get('/workshop/requisition', function () {
            return "Requisition Will Show Here";
        })->name('show.workshop.requisition');

        Route::get('/workshop/requisitions/list', [WorkshopController::class, 'requisitions'])->name('list.workshop.requisition');

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

Route::get('test', function (Request $request) {

    $phone_number = '+260976727570';
    $message = 'Test';

    Log::info('Sending WhatsApp Message');

    $payload = [
        'scenarioKey' => '493D1E0DBB68A871E44517794BB49A11',
        'destinations' => [
            'to' => [
                'phoneNumber' => $phone_number
            ]
        ],
        'whatsApp' => [
            'text' => $message
        ]
    ];

  $response =  Http::withHeaders([
        'Authorization' => 'App 6e00ef45be9cf15c56a74f23b7a9a19f-c63a059e-cef3-4d3f-8f66-acf6d88158f2'
    ])->withOptions([
        'verify' => false
    ])->post('https://zj14w6.api.infobip.com/omni/1/advanced', $payload);

  Log::info($response);

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

    $view_name = 'modules.workshopManagement.jobCard.create_old';

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


