<?php

use App\Http\Controllers\AccidentReporting\AccidentRecordingController;
use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\Configurations\ChargeOutRateController;
use App\Http\Controllers\Configurations\GeneralTablesController;
use App\Http\Controllers\Documents\DocumentController;
use App\Http\Controllers\DriverManagement\DriverController;
use App\Http\Controllers\eTollCardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\RemindersController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Requisitions\FuelRequisitionController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use App\Http\Controllers\Workflow\WorkflowController;
use App\Http\Controllers\WorkshopManagement\MaintenanceController;
use App\Http\Controllers\WorkshopManagement\PdfJobController;
use App\Http\Controllers\WorkshopManagement\WorkshopController;
use App\Services\VehicleManagement\VehicleDetailsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

Route::get('gate/pass', function (Request $request) {
    if (!$request->has('ref')) {
        return redirect(route('home'));
    }

    $vehicle = VehicleDetailsService::getVehicleByReg($request->get('ref'));
    return view('dashboard.pass')
        ->with(compact('vehicle'));

})->name('gate.pass');

Route::get('print/job/card', [PdfJobController::class, "index"])->name('print.job.card');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/error', function (Request $request) {
        return view('error')->with(['error' => $request->get('message')]);
    })->name('error');

    Route::get('/accident/report', [AccidentRecordingController::class, 'create'])
        ->name('accident.reporting');

    Route::get('/accident/list', [AccidentRecordingController::class, 'list'])
        ->name('accident.list');

    Route::get('/accident/types', [AccidentRecordingController::class, 'getAccidentTypes'])
        ->name('accident.types');

    Route::get('/accident/natures', [AccidentRecordingController::class, 'getAccidentNatures'])
        ->name('accident.natures');

    Route::post('/accident/save/report', [AccidentRecordingController::class, 'store'])
        ->name('accident.store');

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

        /************ roles ************/

        Route::post('/roles/assign/permission', [RolesController::class, 'assignPermission'])
            ->name('roles.assign.permission');

        Route::post('/roles/revoke/permission', [RolesController::class, 'revokePermission'])
            ->name('roles.revoke.permission');

        Route::resource('roles', RolesController::class);

        /************ permission ************/
        Route::resource('permissions', PermissionsController::class);
    });

    Route::group(['prefix' => 'system-configuration'], function () {

        /** GENERAL TABLES */
        Route::group(['prefix' => 'general'], function () {
            Route::get('/open-view', [GeneralTablesController::class, "openFormTypeView"])
                ->name('configuration.general.table');
            Route::get('/types', [GeneralTablesController::class, "show"]);
            Route::post('/general_tables', [GeneralTablesController::class, "save"])
                ->name('save.data');
            Route::post('/editRecord', [GeneralTablesController::class, "editRecord"])
                ->name('edit.data');
            Route::post('/deleteRecord', [GeneralTablesController::class, "deleteRecord"])
                ->name('delete.data');
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

        Route::get('vehicle/charge-outrate', [ChargeOutRateController::class, 'index'])
            ->name('charge.out.rate');

        Route::post('save/charge-outrate', [ChargeOutRateController::class, 'store'])
            ->name('save.charge.out.rate');
    });

    Route::group(['prefix' => 'requisitions'], function () {
        Route::get('/fuel', [FuelRequisitionController::class, 'create'])
            ->name('new.fuel.requisition');

        Route::get('/fuel/approve', [FuelRequisitionController::class, 'show'])
            ->name('show.fuel.requisition');

        Route::post('/fuel/save', [FuelRequisitionController::class, 'store'])
            ->name('save.fuel.requisition');

        Route::get('/fuel-requisitions/list', [FuelRequisitionController::class, 'index'])
            ->name('list.fuel.requisition');

        Route::post('/fuel/odometer/validation', [FuelRequisitionController::class, 'validateOdometer'])
            ->name('fuel.odometer.validation');

        Route::post('/fuel/last/requisition', [FuelRequisitionController::class, 'latestRequisition'])
            ->name('fuel.last.requisition');

        Route::get('intercity/distance', [FuelRequisitionController::class, 'getDistance'])
            ->name('intercity.distance');

        Route::post('/workflow/fuel/approve', [WorkflowController::class, 'processFuelRequisitionApproval'])
            ->name('workflow.approve');

    });

    Route::get('searchProjects', [ProjectsController::class, 'findProjectByCode'])
        ->name('search.project');

    Route::group(['prefix' => 'workshop-management'], function () {

        Route::get('workshops/list', [WorkshopController::class, 'index'])
            ->name('workshop.list');

        Route::get('workshop/section', [WorkshopController::class, 'sections'])
            ->name('workshop.sections');

        Route::get('workshops/list/json', [WorkshopController::class, 'getActiveWorkShops'])
            ->name('all.workshop.list');

        Route::get('fuel-levels/list/json', [MaintenanceController::class, 'getFuelLevels'])
            ->name('fuels.levels');

        /** Job Card Processing **/
        Route::group(['prefix' => 'maintenance'], function () {

            Route::get('open/job-card', [MaintenanceController::class, 'create'])
                ->name('show.job.card');

            Route::get('view/job-card', [MaintenanceController::class, 'view'])
                ->name('view.job.card');

            // front desk
            Route::get('vehicle/workshop/checkin', [MaintenanceController::class, 'start'])
                ->name('vehicle.workshop.checkin');

            Route::post('vehicle/workshop/checkin', [MaintenanceController::class, 'createTaskForWorkShopSupervisor'])
                ->name('vehicle.workshop.checkin');

            Route::post('assessment/acknowledgment', [MaintenanceController::class, "eSign"])
                ->name('sign.assessment');

            // supporting
            Route::get('vehicles-in-workshop/list', [MaintenanceController::class, 'list'])
                ->name('workOrder.list');

            Route::get('all/job-card/list', [MaintenanceController::class, 'list'])
                ->name('jobCard.list');

            Route::get('job-card/show', [MaintenanceController::class, 'showJobCard'])
                ->name('job.card.show');

            // delete defect
            Route::post('/deleteRecord', [MaintenanceController::class, "deleteRecord"])
                ->name('delete.defect.record');

            Route::post('/deleteMaterialRecord', [MaintenanceController::class, "deleteMaterialRecord"])
                ->name('delete.material.record');

            Route::post('/deleteServiceRecord', [MaintenanceController::class, "deleteServiceRecord"])
                ->name('delete.service.record');

            Route::post('save/job/card/header', [MaintenanceController::class, 'saveJobCardHeader'])
                ->name('save.job.card');

            Route::post('save/job/card/accessories', [MaintenanceController::class, 'saveJobCardAccessories'])
                ->name('job_card.accessories.checkin');

            Route::post('save/job-card/defects', [MaintenanceController::class, 'saveJobCardDefects'])
                ->name('defects.job_card');

            Route::post('save/material/requisition', [MaintenanceController::class, 'saveJobCardMaterialRequisition'])
                ->name('process.requisition');

            Route::post('save/material/reservation', [MaintenanceController::class, 'saveWorkShopMaterialReservation'])
                ->name('save.material.reservation');

            Route::post('save/services/requisition', [MaintenanceController::class, 'saveJobCardServiceRequest'])
                ->name('process.service.requisition');

            Route::post('save/service/reservation', [MaintenanceController::class, 'saveWorkShopServicesReservation'])
                ->name('save.service.reservation');

            Route::post('save/job/assignment', [MaintenanceController::class, 'saveJobCardWorkAssignments'])
                ->name('save.job.assignment');

            Route::post('save/job/reassignment', [MaintenanceController::class, 'saveJobCardWorkReassignments'])
                ->name('save.job.reassignment');

            Route::get('exit/vehicle/from/workshop', [MaintenanceController::class, 'exitWorkShop'])
                ->name('exit.from.card');

            Route::post('close/job-card', [MaintenanceController::class, 'closeJobCard'])
                ->name('save.exit.from.workshop');

           /* Route::get('parts-selection', [MaintenanceController::class, 'partsSelection'])
                ->name('parts.selection');*/

            Route::get('job-card/accessories', [MaintenanceController::class, 'showAccessoriesTab'])
                ->name('accessories.job.card');

            Route::get('workOrder/job-card/defects', [MaintenanceController::class, 'defectsTab'])
                ->name('defects.job.card');

            Route::get('open/job-card/closure', [MaintenanceController::class, 'openJobCardClosure'])
                ->name('show.workorder.closure');

            Route::post('get/reservations', [MaintenanceController::class, 'getReservedMaterialAndServices'])
                ->name('load.reservations');

            Route::post('attach/to/job-card', [MaintenanceController::class, 'attachReservedArticlesToJobCard'])
                ->name('attach.reservations.card');

            Route::post('store', function (Request $request) {

                Http::asForm()->post(
                    'http://example.com/users',
                    [
                        'name' => 'Sara',
                        'role' => 'Privacy Consultant',
                    ]);

                return response()->json(
                    [
                        'state' => 'success',
                        'payload' => $request->all()
                    ]
                );
            })->name('petty.cash.store');

        });

        Route::get('/bookings/list', [ReservationController::class, 'list'])->name('list.booking');
        Route::get('/workshop/booking', [ReservationController::class, 'create'])->name('new.booking');
        Route::get('/workshop/requisitions', [WorkshopController::class, 'requisitions'])
            ->name('list.workshop.requisition');

        // STORES REQUISITIONS
        Route::get('/workshop/approve', [MaintenanceController::class, 'show'])
            ->name('show.workshop.requisition');

        Route::post('approve/stores/requisition/', [WorkflowController::class, 'processStoresRequisitionApproval'])
            ->name('stores.requisition.approve');
    });

    Route::group(['prefix' => 'driver-management'], function () {
        Route::get('driver/driver', [DriverController::class, 'create'])->name('driver.create');

        Route::post('driver/save', [DriverController::class, 'store'])->name('save.driver');

        Route::get('driver/show', [DriverController::class, 'show'])->name('driver.show');

        Route::get('driver/list', [DriverController::class, 'driverList'])->name('driver.list');

        Route::post('driver/find', [DriverController::class, 'findDriver'])
            ->name('driver.search');
    });

    Route::get('load/procurement/articles', [MaintenanceController::class, 'searchArticle'])
        ->name('load.articles');

    Route::get('get/procurement/articles', [MaintenanceController::class, 'getArticlesByType'])
        ->name('get.articles');

    Route::get('load/article/details', [ProcurementSystemIntegrationController::class, "getArticleDetails"])
        ->name('load.article.details');

    Route::post('get/workshop/store-purchase-office', [MaintenanceController::class, 'getStoreAndPurchaseOffice'])
        ->name('get.store.purchase_office');

    Route::get('document/followup', [DocumentController::class, 'documentFollowup'])
        ->name('document.followup');

    Route::post('document/audit/trail', [DocumentController::class, 'documentAuditTrail'])
        ->name('document.audit.trail');

    Route::group(['prefix' => 'reminders'], function () {
        Route::post('list', [RemindersController::class, 'index'])->name('reminder.list');

        // Renewals
        Route::get('renewal/create', [RemindersController::class, 'createRenewalReminder'])
            ->name('reminder.renewal.new');
        Route::post('renewal/save', [RemindersController::class, 'storeRenewalReminder'])
            ->name('reminder.renewal.save');

        // Service
        Route::get('service/create', [RemindersController::class, 'createServiceReminder'])
            ->name('reminder.service.new');
        Route::post('service/save', [RemindersController::class, 'storeServiceReminder'])
            ->name('reminder.service.save');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::get('fuel/cost', [ReportsController::class, 'fuelCost'])->name('reports.fuel.requisitions');

        Route::get('data/fuel/cost', [ReportsController::class, 'getFuelCost'])->name('reports.fuel.data');

        Route::get('vehicle/status', [ReportsController::class, 'vehicleByStatus'])
            ->name('reports.vehicle.status');
    });

    Route::get('e-toll/cards', [eTollCardController::class, 'create'])->name('e-toll.card');

    Route::post('save/e-toll/cards', [eTollCardController::class, 'store'])->name('e-toll.card.save');

    Route::get('e-toll/cards/list', [eTollCardController::class, 'list'])->name('e-toll.card.list');

    Route::get('e-toll/cards/transactions', [eTollCardController::class, 'uploadTransaction'])
        ->name('e-toll.card.transaction');

    Route::post('save/e-toll/cards/transactions', [eTollCardController::class, 'saveTransaction'])
        ->name('e-toll.card.save.transactions');

    Route::get('e-toll/cards/report', [eTollCardController::class, 'report'])->name('e-toll.card.report');

});


