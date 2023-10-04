<?php

namespace App\Services\VehicleManagement;

use App\Helpers\StatusHelper;
use App\Models\VehicleManagement\EngineDetail;
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
        $periodFrom = Carbon::parse($request->startDate);

        DB::beginTransaction();

        $registrationNumber = $request->input('vehicleRegistration');
        $allocationAmount = $request->get('allocationAmount');

        FuelAllocation::where("reg_no", $registrationNumber)
            ->update(["deleted_at" => Carbon::now()]);

        $periodTo = $request->get('endDate');

        FuelAllocation::create([
            'created_by' => auth()->user()->staff_no,
            'allocation_amount' => $allocationAmount,
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'status' => StatusHelper::active(),
            'reg_no' => $registrationNumber,
            'justification' => $request->get('remarks'),
            'valid_for' => 7,
            'balance' => $request->get('allocationAmount'),
        ]);

        EngineDetail::where("reg_no", $registrationNumber)
            ->update(["fuel_allocation" => $allocationAmount]);

        DB::commit();
    }
}
