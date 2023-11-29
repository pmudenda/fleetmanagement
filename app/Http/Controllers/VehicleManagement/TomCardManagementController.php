<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Exceptions\DataNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleManagement\TomCardAssignment;
use App\Models\VehicleManagement\TomCardAllocation;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TomCardManagementController extends Controller
{
    public function create(): View
    {
        $tomCardAllocations = DB::table('vm_tom_card_allocations tom_alloc')
            ->leftJoin('sec_users user_a', 'tom_alloc.assigned_by', '=', 'user_a.staff_no')
            ->leftJoin('sec_users user_b', 'tom_alloc.revoked_by', '=', 'user_b.staff_no')
            ->select('tom_alloc.*', 'user_a.name as assigned_by_name', 'user_b.name as revoked_by')
            ->get();
        return view('modules.vehicleManagement.tomcard.create')
            ->with(compact('tomCardAllocations'));
    }

    public function store(TomCardAssignment $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $allocation = TomCardAllocation::where('id',
                '=',
                $request->get('record'))->first();
            if (empty($allocation)) {
                throw new DataNotFoundException("Allocation Record Not Found");
            }

            $comments = $request->get('justification');
            $vehicleRegistration = $allocation->reg_no;

            $allocation->status = StatusHelper::inactive();
            $allocation->revocation_justification = $comments;
            $allocation->date_revoked = Carbon::now();
            $allocation->revoked_by = Auth::user()->staff_no;
            $allocation->save();
            DB::table('vm_vehicle_header')
                ->where('registration_number',
                    '=',
                    $vehicleRegistration)
                ->update(['has_tom_card' => 'N']);
            DB::commit();
            return response()->json([
                'state' => 'success',
                'message' => SystemMessages::TOM_CARD_REVOKED
            ]);
        } catch (\Exception $e) {
            $message = SystemMessages::TOM_CARD_REVOCATION_FAILED;
            if ($e instanceof DataNotFoundException) {
                $message = $e->getMessage();
            }
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => $message
            ]);
        }
    }

    public function revoke(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $allocation = TomCardAllocation::where('id',
                '=',
                $request->get('record'))->first();
            if (empty($allocation)) {
                throw new DataNotFoundException("Allocation Record Not Found");
            }

            $comments = $request->get('justification');
            $vehicleRegistration = $allocation->reg_no;

            $allocation->status = StatusHelper::inactive();
            $allocation->revocation_justification = $comments;
            $allocation->date_revoked = Carbon::now();
            $allocation->revoked_by = Auth::user()->staff_no;
            $allocation->save();
            DB::table('vm_vehicle_header')
                ->where('registration_number',
                    '=',
                    $vehicleRegistration)
                ->update(['has_tom_card' => 'N']);
            DB::commit();
            return response()->json([
                'state' => 'success',
                'message' => SystemMessages::TOM_CARD_REVOKED
            ]);
        } catch (\Exception $e) {
            $message = SystemMessages::TOM_CARD_REVOCATION_FAILED;
            if ($e instanceof DataNotFoundException) {
                $message = $e->getMessage();
            }
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => $message
            ]);
        }
    }
}
