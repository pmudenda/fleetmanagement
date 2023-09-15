<?php

namespace App\Services\VehicleManagement;

use App\Constants\ComparisonOperator;
use App\Enums\InsuranceState;
use App\Enums\Modules;
use App\Helpers\StatusHelper;
use App\Models\Common\File;
use App\Models\VehicleManagement\Insurance;
use App\Models\VehicleManagement\VehicleAccessory;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleDetailsService
{
    public static function getAllVehicles(): LengthAwarePaginator
    {
        $query = (new VehicleDetailsService)->getVehicleDataQuery();
        return $query
            ->orderBy('v_header.status')
            ->paginate(20);

    }

    public static function getVehicleByReg(mixed $ref)
    {
        return (new VehicleDetailsService)->getBasicVehicleDetails($ref);
    }

    public function getAllVehiclesByStatus(array $array): Collection
    {
        $query = (new VehicleDetailsService)->getVehicleDataQuery();
        return $query
            ->whereIn('v_header.status', $array)
            ->orderBy('v_header.created_at', 'desc')
            ->get();
    }

    private function getVehicleDataQuery(): Builder
    {
        return DB::table('VM_VEHICLE_HEADER v_header')
            ->leftJoin('CONFIG_STATUSES',
                'v_header.status',
                '=', 'CONFIG_STATUSES.code')
            ->leftJoin('VM_ASSIGNMENTS v_asgnment',
                'v_header.id',
                '=',
                'v_asgnment.vehicle_header_id')
            ->leftJoin('VM_CHASSIS_DETAILS',
                'v_header.id',
                '=',
                'VM_CHASSIS_DETAILS.vehicle_header_id')
            ->leftJoin('VM_ENGINE_DETAILS eng_det',
                'v_header.id',
                '=',
                'eng_det.vehicle_header_id')
            ->where('CONFIG_STATUSES.MODULE',
                '=', Modules::VEHICLE->value)
            ->select(
                'v_header.on_boarding_status',
                'v_header.has_tom_card',
                'v_header.created_at',
                'v_header.registration_number',
                'v_header.body_type_name',
                'v_header.model_name',
                'v_header.model_code',
                'v_header.brand_name',
                'v_header.status',
                'v_header.mileage',
                'v_asgnment.cost_center',
                'v_asgnment.cost_center_name',
                'v_header.id as header_id',
                'eng_det.fuel_allocation',
                'eng_det.fuel_types',
                'CONFIG_STATUSES.name as status_name',
                'v_header.created_name as onboarded_by'
            );
    }

    public function getVehicleDetails($ref): object|null
    {
        try {
            if (empty($ref)) {
                return null;
            }

            Log::info("Vehicle Param received $ref");
            $results = DB::table('VM_VEHICLE_HEADER')->
            where('VM_VEHICLE_HEADER.id', '=', $ref)
                ->leftJoin('VM_ENGINE_DETAILS',
                    'VM_VEHICLE_HEADER.id',
                    '=', 'VM_ENGINE_DETAILS.vehicle_header_id')
                ->leftJoin('VM_ASSIGNMENTS',
                    'VM_VEHICLE_HEADER.id',
                    '=', 'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_CHASSIS_DETAILS',
                    'VM_VEHICLE_HEADER.id',
                    '=', 'VM_CHASSIS_DETAILS.vehicle_header_id')
                ->leftJoin('VM_COST_AND_VALUATIONS',
                    'VM_VEHICLE_HEADER.id',
                    '=',
                    'VM_COST_AND_VALUATIONS.vehicle_header_id')
                ->leftJoin('VM_BODY_AND_WEIGHT_DETAILS',
                    'VM_VEHICLE_HEADER.id',
                    '=',
                    'VM_BODY_AND_WEIGHT_DETAILS.vehicle_header_id')
                ->select('VM_VEHICLE_HEADER.id as headerId',
                    'VM_VEHICLE_HEADER.*',
                    'VM_ASSIGNMENTS.id as assignmentId',
                    'VM_ASSIGNMENTS.*',
                    'VM_ENGINE_DETAILS.id as engineDetailsId',
                    'VM_ENGINE_DETAILS.*',
                    'VM_CHASSIS_DETAILS.id as chassisDetailsId',
                    'VM_CHASSIS_DETAILS.*',
                    'VM_COST_AND_VALUATIONS.id as costAndValuationId',
                    'VM_COST_AND_VALUATIONS.*',
                    'VM_BODY_AND_WEIGHT_DETAILS.id as weightDetailsId',
                    'VM_BODY_AND_WEIGHT_DETAILS.*'
                )->get();

            return $results->first();
        } catch (\Exception $e) {
            Log::info('Failed to Fetch vehicle full details');
            Log::error($e);
            return null;
        }

    }

    public function getVehicleDocuments(mixed $reference)
    {
        return File::where('reference_number', "=", $reference)
            ->where('status', '=', '01')
            ->get();
    }

    public function getBasicVehicleDetails(mixed $vehicleRegistration): object|null
    {
        try {
            $results = DB::table('VM_VEHICLE_HEADER')->
            where('VM_VEHICLE_HEADER.registration_number', $vehicleRegistration)
                ->leftJoin('CONFIG_STATUSES', 'VM_VEHICLE_HEADER.status',
                    '=', 'CONFIG_STATUSES.code')
                ->leftJoin('VM_ASSIGNMENTS', 'VM_VEHICLE_HEADER.id',
                    '=', 'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_CHASSIS_DETAILS', 'VM_VEHICLE_HEADER.id',
                    '=', 'VM_CHASSIS_DETAILS.vehicle_header_id')
                ->leftJoin('VM_ENGINE_DETAILS',
                    'VM_VEHICLE_HEADER.id', '=',
                    'VM_ENGINE_DETAILS.vehicle_header_id')
                ->where('CONFIG_STATUSES.MODULE',
                    '=', Modules::VEHICLE->value)
                ->select('VM_VEHICLE_HEADER.*',
                    'VM_ASSIGNMENTS.*',
                    'VM_ENGINE_DETAILS.fuel_allocation',
                    'CONFIG_STATUSES.name as status_name',
                    'VM_ENGINE_DETAILS.fuel_types'
                )
                ->get();
            return $results->first();
        } catch (\Exception $e) {
            Log::info('Fetch basic vehicle details');
            Log::error($e);
            return null;
        }
    }

    public function getVehicleImages(mixed $reference)
    {
        return File::where('reference_number', "=", $reference)
            ->where('status', '=', StatusHelper::active())
            ->whereIn('module', ['vehicleRegistration', 'Vehicle Registration'])
            ->whereIn('file_type', ["Front View", "Back View", "Right View", "Left View"])
            ->get();
    }

    public function getSubmittedAccessories($vehicleHeaderId): Collection
    {
        try {
            return VehicleAccessory::where('vehicle_header_id', '=', $vehicleHeaderId)
                ->get();
        } catch (\Exception $e) {
            Log::info('Fetch vehicle accessories');
            Log::error($e);
            return collect([]);
        }
    }

    public function getFilteredVehiclesInformation(Request $request): LengthAwarePaginator
    {
        $query = (new VehicleDetailsService)->getVehicleDataQuery();

        if ($request->has('registrationNumber') && $request->filled('registrationNumber')) {
            $registrationNumber = strtoupper(trim($request->get('registrationNumber')));
            $regNumOperator = strtoupper(trim($request->get('regNumOperator')));

            Log::info("Filtering $registrationNumber with $regNumOperator");

            $query->where(function ($q) use ($registrationNumber, $regNumOperator) {
                if (ComparisonOperator::EQUAL == $regNumOperator) {
                    $q->where("v_header.registration_number", "=", $registrationNumber);
                } elseif (ComparisonOperator::STARTS_WITH == $regNumOperator) {
                    $q->where("v_header.registration_number", "LIKE", "{$registrationNumber}%");
                } elseif (ComparisonOperator::ENDS_WITH == $regNumOperator) {
                    $q->where("v_header.registration_number", "LIKE", "%{$registrationNumber}");
                } elseif (ComparisonOperator::CONTAINS == $regNumOperator) {
                    $q->where("v_header.registration_number", "LIKE", "%{$registrationNumber}%");
                }
            });
        }

        if ($request->has('status') && $request->filled('status')) {
            $status = strtoupper(trim($request->get('status')));

            Log::info("Filtering with Status $status");

            $query->where(function ($q) use ($status) {
                $q->where("v_header.status", "=", $status);
            });
        }

        if ($request->has('brand') && $request->filled('brand')) {
            $brand = $request->get('brand');

            Log::info("Filtering with Brand $brand");

            $query->where(function ($q) use ($brand) {
                $q->where("v_header.brand_code", '=', $brand);
            });
        }

        return $query
            ->orderBy('v_header.status')
            ->paginate(20);
    }

    public function getCheckInsurance(mixed $registrationNumber): InsuranceState
    {
        Log::info("Checking Insurance State for $registrationNumber - " . Carbon::today()->toDateString());
        $insurance = Insurance::where(
            'reg_no', '=', $registrationNumber
        )->where(DB::raw('to_date(period_to)'), '>', DB::raw('sysdate'))
            ->first();

        if (empty($insurance)) {

            return InsuranceState::Expired;
        } else {
            Log::info("Insurance Record $insurance->id");
        }

        return InsuranceState::Valid;
    }
}
