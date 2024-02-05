<?php

namespace App\Services\VehicleManagement;

use App\Constants\ComparisonOperator;
use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Enums\Modules;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\VehicleStateException;
use App\Helpers\StatusHelper;
use App\Helpers\VehicleStatus;
use App\Models\Common\File;
use App\Models\Settings\WorkShop;
use App\Models\VehicleManagement\VehicleAccessory;
use App\Models\VehicleManagement\VehicleHeader;
use App\Models\WorkShopManagement\JobCardHeader;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class VehicleDetailsService
{
    const REG = "@reg";

    public function getAllVehicles(): LengthAwarePaginator
    {
        $query = $this->getVehicleDataQuery();
        return $query
            ->orderBy('v_header.status')
            ->paginate(20);

    }

    public function getAllVehiclesQuery()
    {
        $query = $this->getVehicleDataQuery();
        return $query
            ->orderBy('v_header.status');

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

            $results = DB::table('VM_VEHICLE_HEADER header')->where(
                'header.id',
                QueryComparisonOperator::EQUALS,
                $id)
                ->leftJoin('VM_ENGINE_DETAILS engine_detail',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'engine_detail.vehicle_header_id')
                ->leftJoin('VM_ASSIGNMENTS as assign',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'assign.vehicle_header_id')
                ->leftJoin('VM_CHASSIS_DETAILS chassis_detail',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'chassis_detail.vehicle_header_id')
                ->leftJoin(
                    'VM_COST_AND_VALUATIONS cost_eval',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'cost_eval.vehicle_header_id'
                )
                ->leftJoin('VM_BODY_AND_WEIGHT_DETAILS body_weight',
                    'header.id',
                    QueryComparisonOperator::EQUALS,
                    'body_weight.vehicle_header_id')
                ->select(
                    'header.id as headerId',
                    'header.*',

                    'assign.id as assignmentId',
                    'assign.*',

                    'engine_detail.id as engineDetailsId',
                    'engine_detail.*',

                    'chassis_detail.id as chassisDetailsId',
                    'chassis_detail.*',

                    'cost_eval.id as costAndValuationId',
                    'cost_eval.*',

                    'body_weight.id as weightDetailsId',
                    'body_weight.*',
                )
                ->get();

            Log::info($results->count() . "Vehicle Data Found ");

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
            ->where('status', QueryComparisonOperator::EQUALS,
                StatusHelper::active())
            ->get();
    }

    public function getBasicVehicleDetails(mixed $vehicleRegistration): object|null
    {
        try {
            $results = DB::table('VM_VEHICLE_HEADER')->
            where('VM_VEHICLE_HEADER.registration_number', $vehicleRegistration)
                ->leftJoin('CONFIG_STATUSES',
                    'VM_VEHICLE_HEADER.status',
                    QueryComparisonOperator::EQUALS,
                    'CONFIG_STATUSES.code')
                ->leftJoin('VM_ASSIGNMENTS',
                    'VM_VEHICLE_HEADER.id',
                    QueryComparisonOperator::EQUALS,
                    'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_CHASSIS_DETAILS',
                    'VM_VEHICLE_HEADER.id',
                    QueryComparisonOperator::EQUALS,
                    'VM_CHASSIS_DETAILS.vehicle_header_id')
                ->leftJoin('VM_ENGINE_DETAILS',
                    'VM_VEHICLE_HEADER.id',
                    QueryComparisonOperator::EQUALS,
                    'VM_ENGINE_DETAILS.vehicle_header_id')
                ->where('CONFIG_STATUSES.MODULE',
                    QueryComparisonOperator::EQUALS,
                    Modules::VEHICLE->value)
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
            ->where('status', QueryComparisonOperator::EQUALS,
                StatusHelper::active())
            ->whereIn('module', ['vehicleRegistration', 'Vehicle Registration'])
            ->whereIn('file_type', ["Front View", "Back View", "Right View", "Left View"])
            ->get();
    }

    public function getSubmittedAccessories($vehicleHeaderId): Collection
    {
        try {
            return VehicleAccessory::where('vehicle_header_id',
                QueryComparisonOperator::EQUALS,
                $vehicleHeaderId)
                ->get();
        } catch (\Exception $e) {
            Log::info('Fetch vehicle accessories');
            Log::error($e);
            return collect([]);
        }
    }

    public function getFilteredVehiclesInformationQuery(Request $request)
    {
        $query = $this->getVehicleDataQuery();

        if ($request->has('registrationNumber') && $request->filled('registrationNumber')) {
            $registrationNumber = strtoupper(trim($request->get('registrationNumber')));
            $regNumOperator = strtoupper(trim($request->get('regNumOperator')));

            Log::info("Filtering $registrationNumber with $regNumOperator");

            $query->where(function ($q) use ($registrationNumber, $regNumOperator) {
                if (ComparisonOperator::EQUAL == $regNumOperator) {
                    $q->where("v_header.registration_number",
                        QueryComparisonOperator::EQUALS,
                        $registrationNumber);
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
                $q->where("v_header.status",
                    QueryComparisonOperator::EQUALS,
                    $status);
            });
        }

        if ($request->has('hasTomCard') && $request->filled('hasTomCard')) {
            $hasTomCard = strtoupper(trim($request->get('hasTomCard')));

            Log::info("Filtering with Status $hasTomCard");

            $query->where(function ($q) use ($hasTomCard) {
                $q->where("v_header.has_tom_card",
                    QueryComparisonOperator::EQUALS,
                    $hasTomCard);
            });
        }

        if ($request->has('brand') && $request->filled('brand')) {
            $brand = $request->get('brand');

            Log::info("Filtering with Brand $brand");

            $query->where(function ($q) use ($brand) {
                $q->where("v_header.brand_code",
                    QueryComparisonOperator::EQUALS,
                    $brand);
            });
        }

        return $query
            ->orderBy('v_header.status');
    }

    /**
     * Verifies Vehicle is Active otherwise throws exception
     * @param $reference
     * @return void
     * @throws VehicleStateException
     */
    public function verifyVehicleIsActive($reference): void
    {
        $allowedStatus = [StatusHelper::active(), VehicleStatus::vehicleInWorkshop()];

        $vehicle = VehicleHeader::where(
            "registration_number",
            QueryComparisonOperator::EQUALS,
            $reference)->first();

        if (empty($vehicle) || !in_array($vehicle->status, $allowedStatus)) {
            throw new VehicleStateException(
                ErrorMessages::getMessage("err_0004")
            );
        }
    }

    /**
     * @throws DataNotFoundException
     */
    public function getVehicleStateDetails($registrationNumber): array
    {
        if (empty($registrationNumber)) {
            throw new BadRequestException(
                'Missing required parameter'
            );
        }

        // determine material type in form of fuel
        $vehicle = $this->getBasicVehicleDetails(
            $registrationNumber
        );

        if (empty($vehicle)) {
            throw new DataNotFoundException(
                'Vehicle not found'
            );
        }

        $vehicleState = '';
        if ($vehicle->on_boarding_status != StatusHelper::onboardingComplete()) {
            $vehicleState = str_replace(
                self::REG,
                $vehicle->registration_number,
                SystemMessages::vehiclePendingOnboardingCompletion()
            );
        } elseif ($vehicle->status == VehicleStatus::vehicleInWorkshop()) {
            $jobCard = JobCardHeader::where('reg_no',
                QueryComparisonOperator::EQUALS,
                $vehicle->registration_number)->first();

            $workshopName = "";
            if (!empty($jobCard) && !empty($jobCard->workshop_code)) {
                $workshopName = WorkShop::where('workshop_code',
                    $jobCard->workshop_code)
                    ->first()->workshop_name;
            }

            $vehicleState = str_replace(self::REG,
                $vehicle->registration_number,
                str_replace("@workshop",
                    $workshopName,
                    SystemMessages::vehicleInWorkshop())
            );
        } elseif ($vehicle->status != StatusHelper::active()) {
            $vehicleState = str_replace(self::REG,
                $vehicle->registration_number,
                str_replace("@state",
                    $vehicle->status_name,
                    ErrorMessages::getMessage('err_0029'))
            );
        }
        return array($vehicle, $vehicleState);
    }
}
