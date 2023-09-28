<?php

use App\Http\Controllers\FuelManagement\FuelRequisitionController;
use App\Http\Controllers\Workflow\WorkflowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth','is.active','change.password'],
    'prefix' => 'fuel-management'],
    function () {

        Route::get('/', [FuelRequisitionController::class, 'create'])
            ->name('new.fuel.requisition');

        Route::post('/requisition/save', [FuelRequisitionController::class, 'store'])
            ->name('save.fuel.requisition');

        Route::get('/requisition/list', [FuelRequisitionController::class, 'list'])
            ->name('list.fuel.requisition');

        Route::get('requisition/approve', [FuelRequisitionController::class, 'show'])
            ->name('show.fuel.requisition');

        Route::get('requisition/edit', [FuelRequisitionController::class, 'edit'])
            ->name('edit.fuel.requisition');

        Route::post('requisition/resubmit', [FuelRequisitionController::class, 'resubmit'])
            ->name('resubmit.fuel.requisition');

        Route::post('requisition/latest', [FuelRequisitionController::class, 'findLatestRequisition'])
            ->name('fuel.last.requisition');

        Route::post('/odometer/validation', [FuelRequisitionController::class, 'validateOdometer'])
            ->name('fuel.odometer.validation');

        Route::post('/workflow/approve', [WorkflowController::class, 'processFuelRequisitionApproval'])
            ->name('workflow.fuel.approve');

    });



