<?php

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Helpers\StatusHelper;
use App\Http\Controllers\API\BusinessUnitsController;
use App\Http\Controllers\API\CostCenterController;
use App\Http\Controllers\API\LocationsController;
use App\Http\Controllers\API\OrganizationalUnitsController;
use App\Http\Controllers\API\ProcurementSystemIntegrationController;
use App\Http\Controllers\API\RoadTransportSafetyAgencyIntegrationController;
use App\Http\Controllers\OrganizationStructure\BusinessAreasController;
use App\Http\Controllers\OrganizationStructure\DirectoratesController;
use App\Models\Reference\GtaVehicle;
use App\Models\Settings\GeneralTable;
use App\Models\WorkShopManagement\WorkShopTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/en'], function (): void {
    /** Brands API **/

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

    Route::post('license-verification', [RoadTransportSafetyAgencyIntegrationController::class, 'verifyLicenseDetails'])->name('license.details.verification');


    Route::get('load/vehicle/systems', function (Request $request) {
        try {
            $workShopTableData = WorkShopTable::where('type_code', $request->get('key'))
                ->where('status', '=', StatusHelper::active())
                ->get();
            return response()->json([
                'success' => !empty($workShopTableData),
                'payload' => $workShopTableData
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => ErrorMessages::getMessage('')
            ]);
        }
    })->name('load.vehicle.systems');

    Route::get('load/defectsCategory', function (Request $request) {
        try {
            Log::info('Request filter ' . $request->get('key'));

            $workShopTableData = WorkShopTable::where('type_code', 'WCT')
                //->where('status', '=', 1)
                ->where('parent', $request->get('key'))->get();

            return response()->json([
                'success' => !empty($workShopTableData),
                'payload' => $workShopTableData
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    })->name('load.defects.category');

    Route::get('load/defects', function (Request $request) {
        try {
            Log::info('Request filter ' . $request->get('key'));

            $workShopTableData = WorkShopTable::where('type_code', 'WDF')
                ->where('status', '=', StatusHelper::active())
                ->where('parent', $request->get('key'))->get();

            return response()->json([
                'success' => !empty($workShopTableData),
                'payload' => $workShopTableData
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    })->name('load.defects');

    Route::get('load/workshop/section', function (Request $request) {
        try {

            $workShopSection = GeneralTable::where('type', ConfigurationTypes::WORK_SHOP_SECTION)
                ->where('parent', $request->get('key'))
                ->where('status', '=', StatusHelper::active())
                ->get();

            return response()->json([
                'success' => !empty($workShopSection),
                'payload' => $workShopSection
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    })->name('load.workshop.section');

    Route::get('load/licence/classes', function (Request $request) {
        try {

            $licenseCategory = GeneralTable::where('type', ConfigurationTypes::LICENSE_CLASS)
                ->where('active', '=', "1")
                ->get();

            return response()->json([
                'success' => !empty($licenseCategory),
                'payload' => $licenseCategory
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    })->name('vehicle.licence.classes');

    Route::get('transmission/types', function (Request $request) {
        try {
            $transmissionTypes =
                GeneralTable::where('type', ConfigurationTypes::TRANSMISSION_TYPE)
                    ->where('active', '=', "1")
                    ->get();

            return response()->json([
                'success' => !empty($transmissionTypes),
                'payload' => $transmissionTypes
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'payload' => [],
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    })->name('transmission.types');

});
