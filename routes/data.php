<?php

use App\Http\Controllers\API\BusinessUnitsController;
use App\Http\Controllers\API\CostCenterController;
use App\Http\Controllers\API\LocationsController;
use App\Http\Controllers\API\OrganizationalUnitsController;
use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\OrganizationStructure\BusinessAreasController;
use App\Http\Controllers\OrganizationStructure\DirectoratesController;
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'v1/en'], function (): void {
    /** Brands API **/
    /** USERS **/
    /* Route::get('users', function (Request $request) {
         try {
             $data = User::get();
             return response()->json([
                 'state' => 'success',
                 'payload' => $data
             ]);
         } catch (Exception $e) {
             Log::error($e);
             return response()->json([
                 'state' => 'failure',
                 'payload' => []
             ]);
         }
     })->name('api.users.list');*/

    /* Route::get('users/{key}', function (Request $request, string $id, string $key = null) {
         try {
             $data = User::get();
             return response()->json([
                 'state' => 'success',
                 'payload' => $data
             ]);
         } catch (Exception $e) {
             Log::error($e);
             return response()->json([
                 'state' => 'failure',
                 'payload' => []
             ]);
         }
     })->name('api.user.profile');*/


    /* BUSINESS UNITS*/
    Route::get('business-units', BusinessUnitsController::class)->name('business.units');

    Route::get('organizational-units', OrganizationalUnitsController::class)->name('organizational.units');

    Route::get('directorates', [DirectoratesController::class, 'get'])->name('directorates');

    /* BUSINESS UNITS*/
    Route::get('cost-centers', CostCenterController::class)->name('cost.centers');

    Route::get('business-areas', [BusinessAreasController::class, 'get'])->name('business.areas');

    Route::get('purchase/orders', [ProcurementSystemIntegrationController::class, 'verifyPurchaseOrder'])->name('verify.purchase.order');

    Route::get('suppliers', [ProcurementSystemIntegrationController::class, 'getSuppliers'])->name('suppliers.list');

    Route::get('locations', [LocationsController::class, 'index'])->name('locations');

});
