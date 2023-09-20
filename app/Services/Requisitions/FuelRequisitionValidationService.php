<?php

namespace App\Services\Requisitions;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\RequisitionTypes;
use App\Exceptions\FuelRequisitionException;
use App\Helpers\StatusHelper;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\Common\BusinessUnit;
use App\Models\Common\CostCenter;
use App\Models\Common\OrganizationalUnit;
use App\Models\Security\User;
use App\Services\Integration\ProcurementSystemIntegrationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FuelRequisitionValidationService
{
    const REQ_NO = "@req_no";
    const ODOMETER = "@odometer";
    const DATE_VALID_TO = "@date_valid_to";
    const VEH_REG = "@veh_reg";
    const DATE_FORMAT = "d/m/Y";

    private RequisitionAndTaskCancellation $requisitionAndTaskCancellation;

    private ProcurementSystemIntegrationService $procurementService;

    public function __construct(ProcurementSystemIntegrationService $procurementService,
                                RequisitionAndTaskCancellation      $requisitionAndTaskCancellation)
    {
        $this->procurementService = $procurementService;
        $this->requisitionAndTaskCancellation = $requisitionAndTaskCancellation;
    }

    /**
     * @throws FuelRequisitionException
     */
    public function validateCurrentOdometerAgainstMileageReturn($latestOdometerValue, $userProvidedOdometer): bool
    {
        if ($userProvidedOdometer <= $latestOdometerValue) {
            throw new FuelRequisitionException(str_replace(self::ODOMETER,
                $latestOdometerValue,
                ErrorMessages::getMessage("err_0013")
            ), 1000);
        }

        return true;
    }

    /**
     * @param $responsibleHeadStaffNumber
     * @return void
     * @throws FuelRequisitionException
     */
    public function verifyVehicleResponsibleUserIsActive($responsibleHeadStaffNumber): void
    {
        $responsibleHead = User::where("staff_no", "=", $responsibleHeadStaffNumber)->first();

        if (empty($responsibleHead) || $responsibleHead->con_st_code != StatusHelper::activeUser()) {
            throw new FuelRequisitionException(ErrorMessages::getMessage("err_0003"), 300);
        }
    }

    /**
     * Validates the odometer reading on request is greater than the previous issue
     * @param $latestIssue
     * @param $userProvidedOdometerReading
     * @param $odometerOnLastIssue
     * @param $reg_no
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateOdometerAgainstLastIssue(
        $latestIssue,
        $userProvidedOdometerReading,
        $odometerOnLastIssue,
        $reg_no): void
    {
        Log::info("Odometer on last issue $odometerOnLastIssue");
        Log::info("User Provided reading $userProvidedOdometerReading");
        // verify that odometer reading is not the same as previous requisition
        if ($userProvidedOdometerReading <= $odometerOnLastIssue) {
            throw new FuelRequisitionException(
                str_replace(self::VEH_REG, $reg_no,
                    str_replace(self::ODOMETER,
                        $latestIssue->odometer,
                        str_replace(self::REQ_NO,
                            $latestIssue->st_pur ?? $latestIssue->req_no,
                            ErrorMessages::getMessage("err_0024")))),
                1000);
        }
    }

    /**
     * @param $previousRequisition
     * @param $valid_from
     * @param $reg_num
     * @return void
     * @throws FuelRequisitionException
     */
    public function checkIfPreviousRequisitionPeriodElapsed($previousRequisition,
                                                            $valid_from,
                                                            $reg_num): void
    {
        // check if previous requisition period elapsed
        if ($valid_from->lessThanOrEqualTo(Carbon::parse($previousRequisition->valid_date_to))) {
            throw new FuelRequisitionException(
                str_replace(self::VEH_REG, $reg_num,
                    str_replace(self::DATE_VALID_TO,
                        Carbon::parse($previousRequisition->valid_date_to)->format(self::DATE_FORMAT),
                        str_replace(self::REQ_NO,
                            $previousRequisition->st_pur ?? $previousRequisition->req_no,
                            ErrorMessages::getMessage("err_0002")))),
                999);
        }
    }

    /**
     * @param mixed $registrationNumber
     * @return void
     * @throws FuelRequisitionException
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
            throw new FuelRequisitionException("Business Unit Is Not Active");
        }

        Log::info('Cost Center ' . $assignmentInfo->cost_center);

        $countCc = CostCenter::where('code_cost_center', $assignmentInfo->cost_center)
            ->where("status", "=", StatusHelper::active())
            ->count();

        if ($countCc == 0) {
            throw new FuelRequisitionException(
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
            throw new FuelRequisitionException("User Unit Is Not Active");
        }

    }


    /**
     * @param FuelRequisitionPostRequest $requisitionPostRequest
     * @param mixed $latestActiveRequisition
     * @param array $openRequisitionStatusList
     * @param mixed $registrationNumber
     * @param $validFrom
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateLocalRequisition(
        FuelRequisitionPostRequest $requisitionPostRequest,
        mixed                      $latestActiveRequisition,
        array                      $openRequisitionStatusList,
        mixed                      $registrationNumber,
                                   $validFrom
    ): void
    {
        if ($requisitionPostRequest->get("material_quantity")
            > $requisitionPostRequest->get("fuel_allocation")
        ) {
            throw new FuelRequisitionException("Quantity requested can not be more than allocation");
        }
        if (!empty($latestActiveRequisition)) {

            if (in_array($latestActiveRequisition->status, $openRequisitionStatusList)) {

                if (in_array($latestActiveRequisition->requisition_type,
                    [RequisitionTypes::Normal->value, RequisitionTypes::Override->value])) {

                    throw new FuelRequisitionException(
                        str_replace(self::VEH_REG,
                            $registrationNumber,
                            str_replace(self::DATE_VALID_TO,
                                Carbon::parse($latestActiveRequisition->valid_date_to)
                                    ->format(self::DATE_FORMAT),
                                str_replace(self::REQ_NO,
                                    $latestActiveRequisition->st_pur
                                    ?? $latestActiveRequisition->req_no,
                                    ErrorMessages::getMessage("err_0001")
                                )
                            )
                        )
                    );
                }

                // cancel out of town
                if ($latestActiveRequisition->requisition_type
                    == RequisitionTypes::OutOfTown->value) {
                    // cancel requisition
                    $latestActiveRequisition->status = StatusHelper::cancelled();
                    $latestActiveRequisition->save();

                    $this->procurementService->cancelStoresRequisition(
                        $latestActiveRequisition->st_pur,
                        SystemMessages::NORMAL_REQUISITION_RAISED
                    );

                    //cancel associated task
                    $this->requisitionAndTaskCancellation->cancelAssociatedTask($latestActiveRequisition);
                }

            } else {

                // fully issued
                if (RequisitionTypes::Normal->value == $latestActiveRequisition->requisition_type
                    ||
                    RequisitionTypes::Override->value == $latestActiveRequisition->requisition_type
                ) {
                    $this->checkIfPreviousRequisitionPeriodElapsed(
                        $latestActiveRequisition,
                        $validFrom, $registrationNumber);
                }
            }
        }
    }

    /**
     * @param FuelRequisitionPostRequest $requisitionPostRequest
     * @param mixed $latestActiveRequisition
     * @param array $openRequisitionStatusList
     * @return array
     */
    public function validateOutOfTown(FuelRequisitionPostRequest $requisitionPostRequest,
                                      mixed                      $latestActiveRequisition,
                                      array                      $openRequisitionStatusList
    ): array
    {
        // out of town requisition request amount can be more than allocated
        $validFrom = Carbon::createFromFormat("Y-m-d", $requisitionPostRequest->get("departure_date"));
        $validTo = Carbon::createFromFormat("Y-m-d", $requisitionPostRequest->get("return_date"));

        if (!empty($latestActiveRequisition)) {
            if (in_array($latestActiveRequisition->status, $openRequisitionStatusList)) {
                // cancel requisition
                $latestActiveRequisition->status = StatusHelper::cancelled();
                $latestActiveRequisition->save();

                $this->procurementService->cancelStoresRequisition(
                    $latestActiveRequisition->st_pur,
                    SystemMessages::OUT_OF_TOWN_REQUISITION_RAISED
                );

                //cancel associated task
                $this->requisitionAndTaskCancellation->cancelAssociatedTask($latestActiveRequisition);
            }
        } else {
            Log::info('Nothing found for cancellation');
        }

        return array($validFrom, $validTo);
    }

    /**
     * @param mixed $latestActiveRequisition
     * @param array $openRequisitionStatusList
     * @param mixed $registrationNumber
     * @param $validFrom
     * @param FuelRequisitionPostRequest $requisitionPostRequest
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateOverride(mixed                      $latestActiveRequisition,
                                     array                      $openRequisitionStatusList,
                                     mixed                      $registrationNumber,
                                                                $validFrom,
                                     FuelRequisitionPostRequest $requisitionPostRequest
    ): void
    {
        // if there is no previous requisition, throw error
        if (empty($latestActiveRequisition)) {
            throw new FuelRequisitionException(
                ErrorMessages::getMessage("err_0008")
            );
        }

        if (in_array($latestActiveRequisition->status, $openRequisitionStatusList)) {
            $message = "";

            // override should only be requisitioned when the previous is normal
            if (RequisitionTypes::Override->value == $latestActiveRequisition->requisition_type) {
                $message = ErrorMessages::getMessage("err_0006");
            }

            // override should only be requisitioned when the previous is normal and is partially released
            if (RequisitionTypes::Normal->value == $latestActiveRequisition->requisition_type) {
                $message = ErrorMessages::getMessage("err_0007");
            }

            // override should only be requisitioned when the previous is normal
            if (RequisitionTypes::OutOfTown->value == $latestActiveRequisition->requisition_type) {
                $message = ErrorMessages::getMessage("err_0014");
            }

            throw new FuelRequisitionException(
                str_replace(
                    self::VEH_REG,
                    $registrationNumber,
                    str_replace(
                        self::DATE_VALID_TO,
                        Carbon::parse($latestActiveRequisition->valid_date_to)->format(self::DATE_FORMAT),
                        str_replace(
                            self::REQ_NO,
                            $latestActiveRequisition->st_pur
                            ?? $latestActiveRequisition->req_no,
                            $message
                        )
                    )
                )
            );
        }

        // if latest previous is override or out of town, fail
        if (RequisitionTypes::Override->value == $latestActiveRequisition->requisition_type
            || RequisitionTypes::OutOfTown->value == $latestActiveRequisition->requisition_type
        ) {
            throw new FuelRequisitionException(
                str_replace(
                    self::VEH_REG,
                    $registrationNumber,
                    str_replace(
                        self::DATE_VALID_TO,
                        Carbon::parse($latestActiveRequisition->valid_date_to)->format(self::DATE_FORMAT),
                        str_replace(
                            self::REQ_NO,
                            $latestActiveRequisition->st_pur ?? $latestActiveRequisition->req_no,
                            ErrorMessages::getMessage("err_0006")
                        )
                    )
                )
            );
        }

        // check if your request date is before the end of previous requisition,
        // override has to be before expiry of previous requisition
        if (
            RequisitionTypes::Normal->value == $latestActiveRequisition->requisition_type
            && $validFrom->greaterThan(Carbon::parse($latestActiveRequisition->valid_date_to))
        ) {
            throw new FuelRequisitionException(
                str_replace(
                    self::VEH_REG,
                    $registrationNumber,
                    str_replace(
                        self::DATE_VALID_TO,
                        Carbon::parse($latestActiveRequisition->valid_date_to)->format(self::DATE_FORMAT),
                        str_replace(
                            self::REQ_NO,
                            $latestActiveRequisition->st_pur ?? $latestActiveRequisition->req_no,
                            ErrorMessages::getMessage("err_0015")
                        )
                    )
                )
            );
        }

        // quantity requested can not be more than allocated
        if (
            $requisitionPostRequest->get("fuel_allocation")
            <
            $requisitionPostRequest->get("material_quantity")
        ) {
            throw new FuelRequisitionException(
                "Quantity requested can not be more than allocation"
            );
        }
    }

}
