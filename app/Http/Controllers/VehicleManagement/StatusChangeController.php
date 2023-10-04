<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Constants\TableColumns;
use App\Enums\ResponseState;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Settings\general\Status;
use App\Models\VehicleManagement\VehicleHeader;
use App\Models\VehicleManagement\VehicleStatusHistory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StatusChangeController extends Controller
{
    public function create(): View
    {
        $vehicleStatuses = Status::where('module', 'VEH')
            ->whereNotIn('code', ['08', '05'])->get();
        return view('modules.vehicleManagement.statusChange',
            compact('vehicleStatuses'));

    }

    public function store(Request $request): JsonResponse
    {
        try {
            $registrationNumber = $request->get('');
            $status = $request->get('newStatus');
            $remarks = $request->get('remarks');

            VehicleStatusHistory::create([
                'created_by' => auth()->user()->staff_no,
                'code' => 'STATUS',
                TableColumns::REFERENCE => '',
                'page' => '1',
                'description' => $remarks,
                TableColumns::REG_NO => $registrationNumber,
                TableColumns::STATUS => $status,
            ]);

            $vehicleHeader = VehicleHeader::where(
                TableColumns::VEHICLE_HEADER_REGISTRATION,
                QueryComparisonOperator::EQUALS,
                $registrationNumber)
                ->first();

            $vehicleHeader->status = $status;

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::VEHICLE_STATE_CHANGED
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    SystemMessages::VEHICLE_STATE_FAILED
                )
            );
        }
    }


}
