<?php

namespace App\Services\VehicleManagement;

use App\Constants\ComparisonOperator;
use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Enums\DocumentState;
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
    public function getAllVehicles(): LengthAwarePaginator
    {
        $query = $this->getVehicleDataQuery();
        return $query
            ->orderBy('v_header.status')
            ->paginate(20);

    }

    public function getVehicleByReg(mixed $ref)
    {
        return $this->getBasicVehicleDetails($ref);
    }

    public function getAllVehiclesByStatus(array $array): Collection
    {
        $query = $this->getVehicleDataQuery();
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

    public function getVehicleDetailsById($id): object|null
    {
        try {
            if (empty($id)) {
                return null;
            }

            Log::info("Vehicle Param received $id");
            $results = DB::table('VM_VEHICLE_HEADER header')->
            where('header.id',
                QueryComparisonOperator::EQUALS,
                $id)
                ->leftJoin('VM_ENGINE_DETAILS engine_detail',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'engine_detail.vehicle_header_id')
                ->leftJoin('VM_ASSIGNMENTS',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_CHASSIS_DETAILS chassis_detail',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'chassis_detail.vehicle_header_id')
                ->leftJoin('VM_COST_AND_VALUATIONS cost_eval',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'cost_eval.vehicle_header_id')
                ->leftJoin('VM_BODY_AND_WEIGHT_DETAILS body_weight',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'body_weight.vehicle_header_id')
                ->select(
                    'header.id as headerId',
                    'header.id as vehicle_header_id',
                    'header.brand_name',
                    'header.brand_code',
                    'header.model_name',
                    'header.model_code',
                    'header.body_type_code',
                    'header.body_type_name',
                    'header.status',
                    'header.on_boarding_status',
                    'header.registration_type',
                    'header.barcode',
                    'header.has_tom_card',
                    'header.mileage',
                    'header.registration_number',
                    'header.business_unit_code',
                    'header.business_unit_name',
                    'header.location_code',
                    'header.location_name',
                    'header.created_by',
                    'header.created_name',
                    'VM_ASSIGNMENTS.id as assignmentId',
                    'VM_ASSIGNMENTS.*',

                    'engine_detail.id as engineDetailsId',
                    'engine_detail.actual_engine_power',
                    'engine_detail.claimed_engine_power',
                    'engine_detail.engine_brand',
                    'engine_detail.engine_capacity',
                    'engine_detail.engine_type',
                    'engine_detail.fuel_allocation',
                    'engine_detail.fuel_consumption',
                    'engine_detail.fuel_types',
                    'engine_detail.num_batteries',
                    'engine_detail.number_of_cylinders',
                    'engine_detail.tank_capacity',
                    'engine_detail.sub_tank_capacity',
                    'engine_detail.transmission_type',
                    'engine_detail.battery_brand',
                    'engine_detail.battery_size',
                    'engine_detail.battery_power',
                    'engine_detail.front_tyre_size',
                    'engine_detail.number_of_tyres',
                    'engine_detail.rear_tyre_size',
                    'engine_detail.tyre_brand',

                    'chassis_detail.id as chassisDetailsId',
                    'chassis_detail.chassis_number',
                    'chassis_detail.date_on_road',
                    'chassis_detail.engine_number',
                    'chassis_detail.initial_odometer_reading',
                    'chassis_detail.current_odometer_reading',
                    'chassis_detail.inspection_date',
                    'chassis_detail.lst_service_odometer_reading',
                    'chassis_detail.nxt_service_odometer_reading',
                    'chassis_detail.odometer_reset',
                    'chassis_detail.registration_date',
                    'chassis_detail.min_req_driving_license',
                    'chassis_detail.status',
                    'chassis_detail.sticker_registration_number',
                    'chassis_detail.vehicle_charge_out_rate',
                    'chassis_detail.white_book_serial',
                    'chassis_detail.year_of_manufacture',

                    'cost_eval.id as costAndValuationId',
                    'cost_eval.assetNumber',
                    'cost_eval.bookValue',
                    'cost_eval.costOfLicense',
                    'cost_eval.costPrice',
                    'cost_eval.premium',
                    'cost_eval.supplierName',
                    'cost_eval.yearOfPurchase',
                    'cost_eval.created_by',
                    'cost_eval.created_name',
                    'cost_eval.vehicle_header_id',
                    'cost_eval.purchase_order_document',

                    'body_weight.id as weightDetailsId',
                    'body_weight.height',
                    'body_weight.length',
                    'body_weight.numberOfSeats',
                    'body_weight.width',
                    'body_weight.grossWeight',
                    'body_weight.tareWeight',
                    'body_weight.distanceAxle1',
                    'body_weight.distanceAxle2',
                    'body_weight.distanceAxle3',
                    'body_weight.distanceAxle4',
                    'body_weight.trailerWeight2',
                    'body_weight.trailerWeight3',
                    'body_weight.trailerWeight4',
                )->first();

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
        $query = $this->getVehicleDataQuery();

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

    public function getCheckInsurance(mixed $registrationNumber): array
    {
        Log::info("Checking Insurance State for $registrationNumber - " . Carbon::today()->toDateString());
        $insurance = Insurance::where(TableColumns::REG_NO, '=', $registrationNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if (empty($insurance)) {
            return [DocumentState::Expired, null];
        }
        Log::info("Insurance Record $insurance->period_to");


        if (Carbon::now()->isAfter(Carbon::parse($insurance->period_to))) {
            return [DocumentState::Expired, $insurance];
        }

        return [DocumentState::Valid, $insurance];
    }
}
