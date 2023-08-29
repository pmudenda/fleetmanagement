<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\SystemMessages;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleManagement\TomCardAssignment;
use App\Models\VehicleManagement\TomCardAllocation;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TomCardManagementController extends Controller
{
    public function create(): View
    {
        return view('modules.vehicleManagement.tomcard.create');
    }

    public function store(TomCardAssignment $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $expiryDateParts = explode('/', $request->get('expiryDate'));
            $comments = $request->get('comments');
            $expiryDate = Carbon::createFromDate($expiryDateParts[1], $expiryDateParts[0]);
            $vehicleRegistration = $request->get('vehicleRegistration');
            TomCardAllocation::create([
                'reg_no' => $vehicleRegistration,
                'card_number' => $request->get('cardNumber'),
                'period_to' => $expiryDate,
                'status' => StatusHelper::active(),
                'assigned_by' => Auth::user()->staff_no,
                'justification' => $comments
            ]);
            DB::table('vm_vehicle_header')
                ->where('registration_number',
                    '=',
                    $vehicleRegistration)
                ->update(['has_tom_card' => 'Y']);
            DB::commit();
            return response()->json([
                'state' => 'success',
                'message' => SystemMessages::TOM_CARD_ASSIGNED
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => SystemMessages::TOM_CARD_ASSIGNMENT_FAILED
            ]);
        }
    }

    public function list(): View
    {
        return view('modules.vehicleManagement.tomcard.create');
    }
}
