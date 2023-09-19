<?php

use App\Http\Controllers\Requisitions\FuelRequisitionController;
use App\Http\Controllers\Workflow\WorkflowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth',
    'prefix' => 'fuel-management/fuel'],
    function () {
        // new fuel requisition
        Route::get('/', [FuelRequisitionController::class, 'create'])
            ->name('new.fuel.requisition');

        Route::get('/approve', [FuelRequisitionController::class, 'show'])
            ->name('show.fuel.requisition');

        Route::get('/edit', [FuelRequisitionController::class, 'editFuelRequisition'])
            ->name('edit.fuel.requisition');

        Route::post('/requisition/save', [FuelRequisitionController::class, 'submitRequisition'])
            ->name('save.fuel.requisition');

        Route::post('/resubmit', [FuelRequisitionController::class, 'resubmit'])
            ->name('resubmit.fuel.requisition');

        // list [FuelRequisitionController::class, 'index']
        Route::get('/requisitions/list', function () {
            return "Fuel Requisitions List";
        })->name('list.fuel.requisition');

        Route::post('/odometer/validation', [FuelRequisitionController::class, 'validateOdometer'])
            ->name('fuel.odometer.validation');

        Route::post('/latest', [FuelRequisitionController::class, 'latestRequisition'])
            ->name('fuel.last.requisition');

        Route::get('intercity/distance', [FuelRequisitionController::class, 'getDistance'])
            ->name('intercity.distance');

        Route::post('/workflow/approve', [WorkflowController::class, 'processFuelRequisitionApproval'])
            ->name('workflow.approve');

    });



