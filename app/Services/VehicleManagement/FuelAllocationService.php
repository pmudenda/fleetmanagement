<?php

namespace App\Services\VehicleManagement;

use App\Models\VehicleManagement\FuelAllocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelAllocationService
{
    /**
     * @param Request $request
     * @return void
     */
    public static function fuelAllocation(Request $request): void
    {
        $allocation = $request->allocationAmount;
        $periodFrom = Carbon::parse($request->startDate);
        $periodTo = null;
        $endDate = $request->endDate;

        if (empty($endDate)) {
            $periodTo = Carbon::parse($endDate);
        }

        DB::beginTransaction();
        FuelAllocation::create([
            'created_by' => auth()->user()->staff_no,
            'allocation_amount' => $allocation,
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'status',
            'reg_no',
            'user_update',
            'valid_for',
            'balance',
        ]);
        DB::commit();
    }
}
