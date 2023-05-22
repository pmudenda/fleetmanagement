<?php

use App\Http\Controllers\API\BusinessUnitsController;
use App\Http\Controllers\API\CostCenterController;
use App\Http\Controllers\API\OrganizationalUnitsController;
use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\API\RoadTransportSafetyAgencyIntegrationController;
use App\Http\Controllers\Configurations\ConfigVehicleBrandsController;
use App\Http\Controllers\Configurations\VehicleBodyTypesController;
use App\Http\Controllers\OrganizationStructure\BusinessAreasController;
use App\Http\Controllers\OrganizationStructure\DirectoratesController;
use App\Http\Controllers\VehicleManagement\VehicleModelsController;
use App\Models\Security\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth=>sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('license-verification', [RoadTransportSafetyAgencyIntegrationController::class, 'verifyLicenseDetails'])->name('license.details.verification');
