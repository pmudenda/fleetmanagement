<?php

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
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\Workflow\WorkflowController;
use App\Http\Controllers\WorkShopManagement\BookingController;
use App\Http\Controllers\WorkShopManagement\ImprestBuysController;
use App\Http\Controllers\WorkShopManagement\JobCardAcknowledgementController;
use App\Http\Controllers\WorkShopManagement\JobCardController;
use App\Http\Controllers\WorkShopManagement\JobCardItemDeletionController;
use App\Http\Controllers\WorkShopManagement\JobCardLinkingController;
use App\Http\Controllers\WorkShopManagement\MaintenanceController;
use App\Http\Controllers\WorkShopManagement\MaterialReservationController;
use App\Http\Controllers\WorkShopManagement\PdfJobController;
use App\Http\Controllers\WorkShopManagement\ServiceReservationController;
use App\Http\Controllers\WorkShopManagement\WorkShopArticleController;
use App\Http\Controllers\WorkShopManagement\WorkshopController;
use App\Services\VehicleManagement\VehicleDetailsService;
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


Route::group(['middleware' => 'auth', 'prefix' => 'fuel-management'], function () {
    // new fuel requisition
    Route::get('/fuel', [FuelRequisitionController::class, 'create'])
        ->name('new.fuel.requisition');

    Route::get('/fuel/approve', [FuelRequisitionController::class, 'show'])
        ->name('show.fuel.requisition');

    Route::get('/fuel/edit', [FuelRequisitionController::class, 'editFuelRequisition'])
        ->name('edit.fuel.requisition');

    Route::post('/fuel/save', [FuelRequisitionController::class, 'store'])
        ->name('save.fuel.requisition');

    Route::post('/fuel/update', [FuelRequisitionController::class, 'update'])
        ->name('update.fuel.requisition');

    // list
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



