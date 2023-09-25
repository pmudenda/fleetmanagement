<?php

namespace App\Services\Requisitions;

use App\Constants\ErrorMessages;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\OrganisationUnitStateException;
use App\Helpers\StatusHelper;
use App\Models\Common\BusinessUnit;
use App\Models\Common\CostCenter;
use App\Models\Common\OrganizationalUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleAssignmentValidationService
{
    /**
     * @param mixed $registrationNumber
     * @return void
     * @throws OrganisationUnitStateException
     */
    public function checkVehicleAssignedUserUnitAndBuCcStatus(mixed $registrationNumber): void
    {
        $assignmentInfo = DB::table('vm_vehicle_header vh')
            ->where("vh.registration_number", '=', $registrationNumber)
            ->leftJoin('vm_assignments as va',
                'vh.id',
                '=',
                "va.vehicle_header_id")
            ->select('va.business_unit',
                'va.cost_center',
                'vh.business_unit_code as user_unit',
                'va.directorate as zone',
                'va.business_area_code  as area',
                'va.responsible_head_id as responsible',
                'va.vehicleholder as supervisor')
            ->first();

        if (empty($assignmentInfo)) {
            return;
        }

        $countBu = BusinessUnit::where('code_bu', $assignmentInfo->business_unit)
            ->where("status", "=", StatusHelper::active())
            ->count();

        if ($countBu == 0) {
            throw new OrganisationUnitStateException("Business Unit Is Not Active");
        }

        Log::info('Cost Center ' . $assignmentInfo->cost_center);

        $countCc = CostCenter::where('code_cost_center', $assignmentInfo->cost_center)
            ->where("status", "=", StatusHelper::active())
            ->count();

        if ($countCc == 0) {
            throw new OrganisationUnitStateException(
                str_replace('@reg_no', $registrationNumber,
                    str_replace('@cost_center',
                        $assignmentInfo->cost_center,
                        ErrorMessages::getMessage('err_0028')
                    )
                )
            );
        }

        $countUserUnit = OrganizationalUnit::where('code_unit', $assignmentInfo->user_unit)
            ->where("status", "=", StatusHelper::organizationStructureActive())
            ->count();

        if ($countUserUnit == 0) {
            throw new OrganisationUnitStateException("User Unit Is Not Active");
        }

    }

}
