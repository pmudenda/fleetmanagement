<?php

use App\Http\Controllers\AccidentReporting\VehicleRecordingController;
use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\Configurations\ChargeOutRateController;
use App\Http\Controllers\Configurations\GeneralTablesController;
use App\Http\Controllers\Documents\DocumentController;
use App\Http\Controllers\DriverManagement\DriverController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\Requisitions\FuelRequisitionController;
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use App\Http\Controllers\Workflow\WorkflowController;
use App\Http\Controllers\WorkshopManagement\MaintenanceController;
use App\Http\Controllers\WorkshopManagement\WorkshopController;
use App\Http\Requests\ETollCardRequest;
use App\Models\ETollCard;
use App\Models\Workflow\WorkflowApprovalLimit;
use App\Services\FileUploads\FileUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    Route::get('/accident/report', [VehicleRecordingController::class, 'create'])->name('accident.reporting');

    Route::get('/accident/list', [VehicleRecordingController::class, 'list'])->name('accident.list');

    Route::get('/accident/types', [VehicleRecordingController::class, 'accidentTypes'])->name('accident.types');

    Route::get('/accident/natures', [VehicleRecordingController::class, 'accidentNatures'])->name('accident.natures');

    Route::post('/accident/save/report', [VehicleRecordingController::class, 'store'])->name('accident.store');

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

        Route::post('/roles/assign/permission', [RolesController::class, 'assignPermission'])->name('roles.assign.permission');

        Route::post('/roles/revoke/permission', [RolesController::class, 'revokePermission'])->name('roles.revoke.permission');

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

            // supporting
            Route::get('requisitions/jobCard/list', [MaintenanceController::class, 'list'])->name('jobCard.list');

            Route::post('requisitions/jobCard', [MaintenanceController::class, 'create'])->name('save.workshop.requisition');

            //delete defect
            Route::post('/deleteRecord', [MaintenanceController::class, "deleteRecord"])->name('delete.defect.record');

            Route::post('/deleteMaterialRecord', [MaintenanceController::class, "deleteMaterialRecord"])->name('delete.material.record');

            Route::post('save/job/card', [MaintenanceController::class, 'processJobCard'])->name('process.job_card');

            Route::post('save/job/card/accessories', [MaintenanceController::class, 'processJobCardAccessories'])->name('job_card.accessories.checkin');

            Route::post('save/job-card/defects', [MaintenanceController::class, 'processJobCardDefects'])->name('defects.job_card');

            Route::post('save/workshop/material/requisition', [MaintenanceController::class, 'processWorkShopMaterials'])->name('process.requisition');

            Route::post('save/workshop/material/reservation', [MaintenanceController::class, 'processWorkShopMaterialReservation'])->name('save.material.reservation');

            Route::post('save/workshop/services/requisition', [MaintenanceController::class, 'processWorkShopServices'])->name('process.service.requisition');

            Route::post('save/workshop/services/reservation', [MaintenanceController::class, 'processWorkShopServicesReservation'])->name('save.service.reservation');

            Route::get('jobCard/job-card/defects', [MaintenanceController::class, 'defectsTab'])->name('defects.job.card');

            Route::get('exit/vehicle/from/workshop', [MaintenanceController::class, 'exitWorkShop'])->name('exit.from.card');

            Route::get('save/exit/vehicle/from/workshop', [MaintenanceController::class, 'processExitFromWorkShop'])->name('save.exit.from.workshop');

            Route::post('store', function (Request $request) {
                return response()->json(
                    [
                        'state' => 'success',
                        'payload' => $request->all()
                    ]
                );
            })->name('petty.cash.store');

        });

        Route::get('/bookings/list', function () {

            return "Requisitions here";

        })->name('list.booking');

        Route::get('/workshop/booking', function () {
            $user = Auth::user();
            $details = [];
            $materials = [];
            $materialsHeader = null;
            $services = collect([]);
            $view_name = 'modules.workshopManagement.booking.create';

            return view($view_name)
                ->with(compact(
                    'details',
                    'materials',
                    'materialsHeader',
                    'services',
                    'user'
                ));

        })->name('new.booking');

        Route::get('/workshop/requisitions/list', [WorkshopController::class, 'requisitions'])
            ->name('list.workshop.requisition');

        // STORES REQUISITIONS
        Route::get('/workshop/approve', [MaintenanceController::class, 'show'])
            ->name('show.workshop.requisition');

        Route::post('/workflow/stores/requisition/approve', [WorkflowController::class, 'processStoresRequisitionApproval'])
            ->name('stores.requisition.approve');
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

    Route::get('e-toll/cards', function () {
        return view('modules/tollCardManagement/create');
    })->name('e-toll.card');

    Route::post('save/e-toll/cards', function (ETollCardRequest $request) {

        try {
            $user = Auth::user();
            DB::beginTransaction();
            $model = ETollCard::create([
                'batchNumber' => $request->get('batchNumber'),
                'cardScheme' => $request->get('cardScheme'),
                'cardNumber' => $request->get('cardNumber'),
                'cardStatus' => $request->get('cardStatus'),
                'dateIssued' => Carbon::parse($request->get('dateIssued')),
                'expiryDate' => Carbon::parse($request->get('expiryDate')),
                'cvv' => $request->get('cvv'),
                'contactNumber' => $request->get('contactNumber'),
                'assignedTo' => $request->get('assignedTo'),
                'responseHead' => $request->get('responseHead'),
                'responseHeadId' => $request->get('responseHeadId'),
                'comments' => $request->get('comments'),
                'created_by' => $user->staff_no
            ]);


            if (!empty($request->allFiles())) {
                FileUploadService::uploadFile(
                    $request,
                    'supportingDocument',
                    'eTollCard',
                    $model->id,
                    'eTollCard',
                    'eTollCard',
                    $user
                );
            }

            DB::commit();

            return response()->json([
                'payload' => [],
                'state' => 'success'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'payload' => [],
                'state' => 'failed'
            ]);
        }


    })->name('e-toll.card.save');

    Route::get('e-toll/cards/list', function () {
        return view('modules/tollCardManagement/index');
    })->name('e-toll.card.list');
});

Route::get('load/procurement/articles', [MaintenanceController::class, 'searchArticle'])
    ->name('load.articles');

Route::get('get/procurement/articles', [MaintenanceController::class, 'getArticlesByType'])
    ->name('get.articles');

Route::get('load/article/details', [ProcurementSystemIntegrationController::class, "getArticleDetails"])
    ->name('load.article.details');

Route::post('get/workshop/store-purchase-office', [MaintenanceController::class, 'getStoreAndPurchaseOffice'])
    ->name('get.store.purchase_office');

Route::post('document/followup', [DocumentController::class, 'documentFollowup'])
    ->name('document.followup');

Route::post('document/audit/trail', [DocumentController::class, 'documentAuditTrail'])
    ->name('document.audit.trail');

Route::get('test', function (Request $request) {

    /*(config('rights.role_create'))
    (auth()->user()->can(config('rights.role_create')));


    config('rights.role_access');
    dd(auth()->user()->can(config('rights.role_access')));


    config('rights.role_show');
    dd(auth()->user()->can(config('rights.role_show')));


    config('rights.role_edit');
    dd(auth()->user()->can(config('rights.role_edit')));


    config('rights.role_destroy');
    (ddauth()->user()->can(config('rights.role_destroy')));


    config('rights.role_attach');


    dd(auth()->user()->can(config('rights.role_attach')));

    config('rights.role_detach');
    dd(auth()->user()->can(config('rights.role_detach')));

    dd(config('rights'));*/
    $amount = $request->get('amount');
    $user_unit = 'G1500';
    $result = WorkflowApprovalLimit::where('user_unit_code', '=', $user_unit)
        ->where(function($query) use($amount){
            return $query->where('approval_lower_limit', '>=', $amount)
                ->where('approval_upper_limit', '<=', $amount);
        })
        ->first();

    return $result->final_step;
})->
name('barcode.generate');

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


