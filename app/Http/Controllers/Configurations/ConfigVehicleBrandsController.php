<?php

namespace App\Http\Controllers\Configurations;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Constants\TableColumns;
use App\Enums\ResponseState;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleMake;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Settings\vehicle\VehicleBrand;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConfigVehicleBrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get(): JsonResponse
    {
        try {
            $statusList = [StatusHelper::active()];
            $data = VehicleBrand::whereIn(
                TableColumns::STATUS,
                $statusList)->get();

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    null,
                    $data
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    null,
                    []
                )
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleMake $request): JsonResponse
    {
        try {

            $make = VehicleBrand::where('name',
                QueryComparisonOperator::EQUALS,
                trim(strtoupper($request->input('brand_name'))))
                ->first();

            if (!empty($make)) {
                return response()->json(
                    FleetMasterJsonResponse::response(
                        ResponseState::FAILURE->value,
                        false,
                        SystemMessages::DUPLICATE_VEHICLE_MAKE,
                        []
                    )
                );
            }

            VehicleBrand::updateOrCreate(
                [
                    'name' => trim(strtoupper($request->input('brand_name'))),
                ],
                [
                    'name' => trim(strtoupper($request->input('brand_name'))),
                    TableColumns::STATUS => StatusHelper::active(),
                    'guid' => Str::uuid(),
                    'dateCreated' => Carbon::now()
                ]);

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::VEHICLE_MAKE_SUCCESSFUL,
                    []
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    ErrorMessages::getMessage('err_0005'),
                    []
                )
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {

            $configVehicleBrands = VehicleBrand::where(
                'code',
                $request->input('code')
            )->first();

            $configVehicleBrands->status = 'deactivated';

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::RECORD_DELETED,
                    []
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    ErrorMessages::getMessage('err_0005'),
                    []
                )
            );
        }
    }
}
