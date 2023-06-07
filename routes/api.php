<?php

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Helpers\StatusHelper;
use App\Http\Controllers\API\RoadTransportSafetyAgencyIntegrationController;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\reference\GtaVehicle;
use App\Models\WorkShopManagement\WorkShopTable;
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

Route::post('find/vehicle', function (Request $request) {
    try {
        $vehicle = GtaVehicle::where('matricula', $request->get('reg_num'))->get();
        return response()->json([
            'success' => !empty($vehicle),
            'payload' => $vehicle
        ]);
    } catch (Exception $e) {
        Log::error($e);
        return response()->json([
            'success' => false,
            'payload' => [],
            'message' => 'We could not complete processing your request, please try again later'
        ]);
    }
})->name('cleanup.vehicle.find');

Route::get('load/vehicle/systems', function (Request $request) {
    try {
        Log::info('Request filter ' . $request->get('key'));
        //$workShopTableData = [];
        //$query = WorkShopTable::query();

        /*if (!empty($request->get('filter'))) {
            WorkShopTable::where('type_code', $request->get('key'))
                ->where('parent', $request->get('filter'))->get();
        }*/

        $workShopTableData = WorkShopTable::where('type_code', $request->get('key'))->get();
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
        Log::info('Request filter ' . $request->get('filter'));

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
        Log::info('Request filter ' . $request->get('filter'));

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

        $workShopSection = GeneralTableConfigurations::where('type', ConfigurationTypes::WORK_SHOP_SECTION)
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

Route::get('vehicle/licence/classes', function (Request $request) {
    try {

        $licenseCategory = GeneralTableConfigurations::where('type', ConfigurationTypes::LICENSE_CLASS)
            ->where('active', '=', 1)
            ->orWhere('active', '=', true)
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


