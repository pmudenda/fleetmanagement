<?php

namespace App\Http\Controllers\VehicleManagement;

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
            $expiryDateParts = explode('/', $request->get('expiryDate'));
            $comments = $request->get('comments');
            $expiryDate = Carbon::createFromDate($expiryDateParts[1], $expiryDateParts[0]);
            $vehicleRegistration = $request->get('vehicleRegistration');
            TomCardAllocation::create([
                'reg_no' => $vehicleRegistration,
                'card_number' => $request->get('cardNumber'),
                'period_from' => Carbon::now(),
                'period_to' => $expiryDate,
                'status' => StatusHelper::active(),
                'assigned_by' => Auth::user()->staff_no,
                'assignment_justification' => $comments
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
