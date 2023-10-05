<?php

namespace App\Http\Controllers\Configurations;

use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Enums\ResponseState;
use App\Exceptions\BaseException;
use App\Exceptions\DataNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Settings\vehicle\VehicleBodyType;
use App\Services\Logging\HistoryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VehicleBodyTypesController extends Controller
{
    public function get(): JsonResponse
    {
        try {
            $statusList = [StatusHelper::active()];
            $data = VehicleBodyType::whereIn('status', $statusList)->get();
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::RECORD_NOT_FOUND,
                    $data
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    SystemMessages::RECORD_NOT_FOUND
                )
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleBodyType $request): JsonResponse
    {
        try {

            $name = trim(strtoupper($request->input('body_type_name')));

            $vehicleBodyType = VehicleBodyType::where('body_type_name',
                QueryComparisonOperator::EQUALS,
                $name)->first();

            if (!empty($vehicleBodyType)) {
                throw new DataNotFoundException(SystemMessages::RECORD_NOT_FOUND);
            }

            $model = VehicleBodyType::updateOrCreate(
                [
                    'body_type_name' => $name
                ],
                [
                    'status' => StatusHelper::active(),
                    'guid' => Str::uuid(),
                    'dateCreated' => Carbon::now(),
                    'name' => $name,
                    'body_type_name' => $name
                ]);

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::RECORD_SUCCESSFUL,
                    $model
                )
            );

        } catch (Exception $e) {
            Log::error($e);
            $message = SystemMessages::DUPLICATE_RECORD;
            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message
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
            DB::beginTransaction();
            $data = VehicleBodyType::where('guid', $request->input('guid'));

            $before = $data->toArray();

            $data->status = StatusHelper::inactive();
            $data->save();

            DB::commit();

            $after = $data->toArray();
            HistoryService::update($before, $after,
                'Body Type',
                'update',
                $request->get('justification'));

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    false,
                    null,
                    $data
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            $message = "";
            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            }
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message
                )
            );
        }
    }
}
