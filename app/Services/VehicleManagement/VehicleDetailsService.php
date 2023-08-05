<?php

namespace App\Services\VehicleManagement;

use App\Enums\Modules;
use App\Helpers\StatusHelper;
use App\Models\general\File;
use App\Models\VehicleManagement\VehicleAccessory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleDetailsService
{
    public static function getAllVehicles(): Collection
    {
        try {
            return DB::table('VM_VEHICLE_HEADER')
                ->leftJoin('CONFIG_STATUSES', 'VM_VEHICLE_HEADER.status', '=', 'CONFIG_STATUSES.code')
                ->leftJoin('VM_ASSIGNMENTS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_CHASSIS_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_CHASSIS_DETAILS.vehicle_header_id')
                ->leftJoin('VM_ENGINE_DETAILS',
                    'VM_VEHICLE_HEADER.id', '=', 'VM_ENGINE_DETAILS.vehicle_header_id')
                ->where('CONFIG_STATUSES.MODULE', '=', Modules::Vehicle)
                ->select('VM_VEHICLE_HEADER.*',
                    'VM_VEHICLE_HEADER.id as header_id',
                    'VM_ASSIGNMENTS.*',
                    'VM_ENGINE_DETAILS.fuel_allocation',
                    'CONFIG_STATUSES.name as status_name',
                    'VM_VEHICLE_HEADER.created_name as onboarded_by',
                    'VM_ENGINE_DETAILS.fuel_types'
                )->get();
        } catch (\Exception $e) {
            Log::info('Fetch vehicle details');
            Log::error($e);
            return collect([]);
        }
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
                ->leftJoin('VM_ENGINE_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ENGINE_DETAILS.vehicle_header_id')
                ->leftJoin('VM_ASSIGNMENTS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_CHASSIS_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_CHASSIS_DETAILS.vehicle_header_id')
                ->leftJoin('VM_COST_AND_VALUATIONS', 'VM_VEHICLE_HEADER.id', '=', 'VM_COST_AND_VALUATIONS.vehicle_header_id')
                ->leftJoin('VM_BODY_AND_WEIGHT_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_BODY_AND_WEIGHT_DETAILS.vehicle_header_id')
                ->select('VM_VEHICLE_HEADER.id as headerId',
                    'VM_VEHICLE_HEADER.*',
                    'VM_ASSIGNMENTS.id as assignmentId', 'VM_ASSIGNMENTS.*',
                    'VM_ENGINE_DETAILS.id as engineDetailsId', 'VM_ENGINE_DETAILS.*',
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

    public function getBasicVehicleDetails(mixed $vehicle_registration): object|null
    {
        try {
            $results = DB::table('VM_VEHICLE_HEADER')->
            where('VM_VEHICLE_HEADER.registration_number', $vehicle_registration)
                ->leftJoin('CONFIG_STATUSES', 'VM_VEHICLE_HEADER.status', '=', 'CONFIG_STATUSES.code')
                ->leftJoin('VM_ASSIGNMENTS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_CHASSIS_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_CHASSIS_DETAILS.vehicle_header_id')
                ->leftJoin('VM_ENGINE_DETAILS',
                    'VM_VEHICLE_HEADER.id', '=', 'VM_ENGINE_DETAILS.vehicle_header_id')
                ->where('CONFIG_STATUSES.MODULE', '=', Modules::Vehicle)
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

    public function getSubmittedAccessories($vehicle_header_id): Collection
    {
        try {
            return VehicleAccessory::where('vehicle_header_id', '=', $vehicle_header_id)->get();
        } catch (\Exception $e) {
            Log::info('Fetch vehicle accessories');
            Log::error($e);
            return collect([]);
        }
    }
}
