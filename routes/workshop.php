<?php

use App\Http\Controllers\Workflow\WorkflowController;
use App\Http\Controllers\WorkShopManagement\AssessmentAcknowledgementController;
use App\Http\Controllers\WorkShopManagement\BookingController;
use App\Http\Controllers\WorkShopManagement\ImprestBuysController;
use App\Http\Controllers\WorkShopManagement\JobCardController;
use App\Http\Controllers\WorkShopManagement\JobCardItemDeletionController;
use App\Http\Controllers\WorkShopManagement\JobCardLinkingController;
use App\Http\Controllers\WorkShopManagement\MaintenanceController;
use App\Http\Controllers\WorkShopManagement\MaterialReservationController;
use App\Http\Controllers\WorkShopManagement\ServiceReservationController;
use App\Http\Controllers\WorkShopManagement\VehicleAssessmentController;
use App\Http\Controllers\WorkShopManagement\WorkshopController;
use App\Models\WorkShopManagement\JobCardHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'is.active', 'change.password'],
    'prefix' => 'workshop-management'], function () {

    Route::get('workshops/list', [WorkshopController::class, 'index'])
        ->name('workshop.list');

    Route::get('workshop/section', [WorkshopController::class, 'sections'])
        ->name('workshop.sections');

    Route::get('workshops/list/json', [WorkshopController::class, 'getActiveWorkShops'])
        ->name('all.workshop.list');

    Route::get('fuel-levels/list/json', [MaintenanceController::class, 'getFuelLevels'])
        ->name('fuels.levels');

    //Job Card Processing
    Route::group(['prefix' => 'maintenance'], function () {

        Route::get('open/job-card', [MaintenanceController::class, 'create'])
            ->name('show.job.card');

        Route::get('view/job-card', [MaintenanceController::class, 'view'])
            ->name('view.job.card');

        // front desk
        Route::get('vehicle/workshop/checkin', [MaintenanceController::class, 'start'])
            ->name('vehicle.workshop.checkin');

        Route::post('vehicle/workshop/create-task', [MaintenanceController::class, 'createTaskForWorkShopSupervisor'])
            ->name('vehicle.workshop.checkin');

        Route::post('assessment/acknowledgment', AssessmentAcknowledgementController::class)
            ->name('sign.assessment');

        // supporting
        Route::get('vehicles-in-workshop/list', [JobCardController::class, 'list'])
            ->name('workOrder.list');

        Route::get('vehicles-in-workshop/json', function (Request $request) {
            if ($request->ajax()) {
                $data = JobCardHeader::latest()->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">
                                        Edit
                                        </a>
                                        <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">
                                            Delete
                                        </a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return DataTables::of((object)[])->make();
        })->name('job_card.list.json');

        Route::get('all/job-card/list', [JobCardController::class, 'list'])
            ->name('jobCard.list');

        Route::get('closed/job-card/list', [JobCardController::class, 'viewClosedJobCards'])
            ->name('closed.jobCard.list');

        Route::get('job-card/show', [MaintenanceController::class, 'showJobCard'])
            ->name('job.card.show');

        // delete defect
        Route::post('/deleteRecord', [JobCardItemDeletionController::class, "deleteDefectRecord"])
            ->name('delete.defect.record');

        Route::post('/deletePettyCashItem', [ImprestBuysController::class, "deletePettyCashItem"])
            ->name('delete.pettyCashItem.record');

        Route::post('/deleteMaterialRecord', [JobCardItemDeletionController::class, "deleteMaterialRecord"])
            ->name('delete.material.record');

        Route::post('/deleteServiceRecord', [JobCardItemDeletionController::class, "deleteServiceRecord"])
            ->name('delete.service.record');

        Route::post('save/job/card/header', [MaintenanceController::class, 'saveJobCardHeader'])
            ->name('save.job.card');

        Route::post('save/job/card/accessories', [VehicleAssessmentController::class, 'save'])
            ->name('job_card.accessories.checkin');

        Route::post('save/job-card/defects', [MaintenanceController::class, 'saveJobCardDefects'])
            ->name('defects.job_card');

        Route::post('save/material/requisition', [MaterialReservationController::class, 'saveJobCardMaterialRequest'])
            ->name('process.requisition');

        Route::post('save/material/reservation', [MaterialReservationController::class, 'saveMaterialRequest'])
            ->name('save.material.reservation');

        Route::post('save/services/requisition', [ServiceReservationController::class, 'saveJobCardService'])
            ->name('process.service.requisition');

        Route::post('save/service/reservation', [ServiceReservationController::class, 'saveServiceBooking'])
            ->name('save.service.reservation');

        Route::post('save/job/assignment', [MaintenanceController::class, 'saveJobCardWorkAssignments'])
            ->name('save.job.assignment');

        Route::post('save/job/reassignment', [MaintenanceController::class, 'saveJobCardWorkReassignments'])
            ->name('save.job.reassignment');

        Route::get('exit/vehicle/from/workshop', [MaintenanceController::class, 'exitWorkShop'])
            ->name('exit.from.card');

        Route::post('close/job-card', [MaintenanceController::class, 'closeJobCard'])
            ->name('save.exit.from.workshop');


        Route::get('job-card/accessories', [MaintenanceController::class, 'showAccessoriesTab'])
            ->name('accessories.job.card');


        Route::post('get/reservations', [MaintenanceController::class, 'getReservedMaterialAndServices'])
            ->name('load.reservations');

        Route::post('attach/to/job-card', [JobCardLinkingController::class, 'attachReservedArticlesToJobCard'])
            ->name('attach.reservations.card');

        Route::post('store/petty-cash/item', [ImprestBuysController::class, 'saveImprestBuyItems'])
            ->name('petty.cash.store');

    });

    Route::get('/workshop/booking', [BookingController::class, 'create'])
        ->name('new.booking');

    Route::get('/workshop/requisitions', [WorkshopController::class, 'requisitions'])
        ->name('list.workshop.requisition');

    // STORES REQUISITIONS
    Route::get('/workshop/approve', [MaintenanceController::class, 'show'])
        ->name('show.workshop.requisition');

    Route::post('approve/stores/requisition/', [WorkflowController::class, 'processStoresRequisitionApproval'])
        ->name('stores.requisition.approve');

    Route::post('tasks/view', [WorkflowController::class, 'viewTasks'])
        ->name('workflow.task');

    Route::post('get/workshop/store-purchase-office', [MaintenanceController::class, 'getStoreAndPurchaseOffice'])
        ->name('get.store.purchase_office');
});
