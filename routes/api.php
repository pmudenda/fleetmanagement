<?php

use App\Http\Controllers\API\BusinessUnitsController;
use App\Http\Controllers\API\CostCenterController;
use App\Http\Controllers\API\OrganizationalUnitsController;
use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\Configurations\ConfigVehicleBrandsController;
use App\Http\Controllers\Configurations\VehicleBodyTypesController;
use App\Http\Controllers\OrganizationStructure\BusinessAreasController;
use App\Http\Controllers\OrganizationStructure\DirectoratesController;
use App\Http\Controllers\VehicleManagement\VehicleModelsController;
use App\Models\configurations\GeneralTableConfigurations;
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

/*Route::prefix('v1/en')->as('brands:')->middleware(['api'])->group(static function (): void {

});*/

Route::group(['prefix' => 'v1/en'], function (): void {
    /** Brands API **/

    Route::prefix('brands')->middleware(['api'])->group(static function (): void {
        Route::get('/brands', [ConfigVehicleBrandsController::class, 'index'])->name('brands.get');
        Route::post('/store', [ConfigVehicleBrandsController::class, 'store'])->name('brands.save');
        Route::delete('/', [ConfigVehicleBrandsController::class, 'destroy'])->name('brands.delete');
    });

    /** MODELS **/
    Route::post('/models', [VehicleModelsController::class, 'store'])->name('models.save');
    Route::get('/models', [VehicleModelsController::class, 'get'])->name('models.get');
    Route::delete('/models', [VehicleModelsController::class, 'destroy'])->name('models.delete');


    /** BODY TYPES **/
    Route::post('/vehicle/body-types', [VehicleBodyTypesController::class, 'store'])->name('body_type.save');
    Route::get('/vehicle/body-types', [VehicleBodyTypesController::class, 'index'])->name('body_type.get');
    Route::delete('/vehicle/body-types', [VehicleBodyTypesController::class, 'destroy'])->name('body_type.delete');

    /** USERS **/
    Route::get('users', function (Request $request) {
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
    })->name('api.users.list');

    Route::get('users/{key}', function (Request $request, string $id, string $key = null) {
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
    })->name('api.user.profile');


    /* BUSINESS UNITS*/
    Route::get('business-units', BusinessUnitsController::class)->name('business.units');

    Route::get('organizational-units', OrganizationalUnitsController::class)->name('organizational.units');

    Route::get('directorates', [DirectoratesController::class, 'get'])->name('directorates');

    /* BUSINESS UNITS*/
    Route::get('cost-centers', CostCenterController::class)->name('cost.centers');

    Route::get('business-areas',[BusinessAreasController::class, 'get'])->name('business.areas');

    Route::get('purchase/orders',[ProcurementSystemIntegrationController::class, 'verify'])->name('verify.purchase.order');

});
