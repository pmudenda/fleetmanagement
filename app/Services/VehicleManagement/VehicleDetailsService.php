<?php

namespace App\Services\VehicleManagement;

use App\Models\general\File;
use Illuminate\Support\Facades\DB;

class VehicleDetailsService
{
    public function getVehicleDetails($ref): object|null
    {
        if (empty($ref)) {
            return null;
        }
        /*return DB::table('VM_VEHICLE_HEADER')->
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
                'VM_BODY_AND_WEIGHT_DETAILS.*')
            ->first();*/

       return DB::raw('select "VM_VEHICLE_HEADER"."ID" as "HEADERID",
                      "VM_VEHICLE_HEADER".*,
                      "VM_ASSIGNMENTS"."ID" as "ASSIGNMENTID",
                      "VM_ASSIGNMENTS".*,
                      "VM_ENGINE_DETAILS"."ID" as "ENGINEDETAILSID",
                      "VM_ENGINE_DETAILS".*,
                      "VM_CHASSIS_DETAILS"."ID" as "CHASSISDETAILSID",
                      "VM_CHASSIS_DETAILS".*,
                      "VM_COST_AND_VALUATIONS"."ID" as "COSTANDVALUATIONID",
                      "VM_COST_AND_VALUATIONS".*,
                      "VM_BODY_AND_WEIGHT_DETAILS"."ID" as "WEIGHTDETAILSID",
                      "VM_BODY_AND_WEIGHT_DETAILS".*
               from "VM_VEHICLE_HEADER" left join "VM_ENGINE_DETAILS"
                   on "VM_VEHICLE_HEADER"."ID" = "VM_ENGINE_DETAILS"."VEHICLE_HEADER_ID"
                   left join "VM_ASSIGNMENTS" on "VM_VEHICLE_HEADER"."ID" = "VM_ASSIGNMENTS"."VEHICLE_HEADER_ID"
                   left join "VM_CHASSIS_DETAILS"
                       on "VM_VEHICLE_HEADER"."ID" = "VM_CHASSIS_DETAILS"."VEHICLE_HEADER_ID"
                   left join "VM_COST_AND_VALUATIONS"
                       on "VM_VEHICLE_HEADER"."ID" = "VM_COST_AND_VALUATIONS"."VEHICLE_HEADER_ID"
                   left join "VM_BODY_AND_WEIGHT_DETAILS"
                       on "VM_VEHICLE_HEADER"."ID" = "VM_BODY_AND_WEIGHT_DETAILS"."VEHICLE_HEADER_ID"
where "VM_VEHICLE_HEADER"."ID" = '. $ref)->get();
    }

    public function getVehicleDocuments(mixed $reference)
    {
        return File::where('reference_number', "=", $reference)
            ->where('status', '=', '01')
            ->get();
    }

    public function getBasicVehicleDetails(mixed $vehicle_registration): object|null
    {
        return DB::table('VM_VEHICLE_HEADER')->
        where('registration_number', $vehicle_registration)
            //->where('on_boarding_status', $request->vehicle_registration)
            ->leftJoin('VM_ASSIGNMENTS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ASSIGNMENTS.vehicle_header_id')
            ->leftJoin('VM_ENGINE_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ENGINE_DETAILS.vehicle_header_id')
            ->select('VM_VEHICLE_HEADER.*', 'VM_ASSIGNMENTS.*', 'VM_ENGINE_DETAILS.fuel_allocation', 'VM_ENGINE_DETAILS.fuel_types')
            ->first();
    }
}
