<?php

namespace App\Services\WorkShopManagement;

use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Helpers\StatusHelper;
use App\Models\Settings\Accessory;
use App\Models\Settings\GeneralTable;
use App\Models\WorkShopManagement\AssessmentObservation;
use App\Models\WorkShopManagement\WorkShopComment;
use App\Models\WorkShopManagement\WorkShopMaterialHeader;
use App\Models\WorkShopManagement\WorkShopVehicleAccessory;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class JobCardDetailsService
{
    private WorkshopService $workshopService;
    private WorkshopRequisitionService $workshopRequisitionService;

    public function __construct(WorkshopService            $workshopService,
                                WorkshopRequisitionService $workshopRequisitionService)
    {
        $this->workshopService = $workshopService;
        $this->workshopRequisitionService = $workshopRequisitionService;
    }

    public function getFullJobCardDetails($reference): array
    {
        list($repairTypes, $accessories, $workshop_sections) = $this->getWorkshopsRepairTypesAndSections();

        $accessoriesCheckedIn = null;
        $details = null;
        $defects = collect([]);
        $comments = collect([]);
        $officeDetails = null;
        $materials = collect([]);
        $materialsHeader = null;
        $services = collect([]);
        $labour = collect([]);
        $pettyCashItems = collect([]);
        $observation = collect([]);

        if ($reference) {
            list($accessoriesCheckedIn,
                $details,
                $officeDetails,
                $defects,
                $comments,
                $materials,
                $materialsHeader,
                $services,
                $labour,
                $pettyCashItems,
                $observation) = $this->getFullJobCardData($reference);
        }

        return array(
            $repairTypes,
            $accessoriesCheckedIn,
            $accessories,
            $details,
            $workshop_sections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
        );
    }

    /**
     * @return array
     */
    public function getWorkshopsRepairTypesAndSections(): array
    {
        $repairTypes = GeneralTable::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)
            ->where("active",
                QueryComparisonOperator::EQUALS,
                1)
            ->orderBy("name")
            ->get();

        $accessories = Accessory::where(TableColumns::STATUS,
            QueryComparisonOperator::EQUALS,
            StatusHelper::active())
            ->orderBy("name")
            ->get();

        $workshopSections = GeneralTable::where(Constants::TYPE_KEY, ConfigurationTypes::WORK_SHOP_SECTION)
            ->where("active",
                QueryComparisonOperator::EQUALS,
                1)
            ->orderBy("name")
            ->get();

        return array($repairTypes, $accessories, $workshopSections);
    }

    /**
     * @param $reference
     * @return array
     */
    public function getFullJobCardData($reference): array
    {
        $accessoriesCheckedIn = WorkShopVehicleAccessory::where("job_card_no",
            QueryComparisonOperator::EQUALS,
            $reference)
            ->get();

        $details = $this->workshopService->getJobCardDetails($reference);

        $officeDetails = $this->workshopService->getWorkShopPurchaseOfficeAndStore($details->workshop_code);

        $vehicleSys = 'VEH_SYS';
        $defectCategory = 'WCT';
        $defects = DB::table("wm_vehicle_defects def")
            ->join("wm_workshop_tables wckt", function (JoinClause $join) use ($defectCategory) {
                $join->on("def.defect_category_code",
                    QueryComparisonOperator::EQUALS,
                    "wckt.code")
                    ->where(function ($query) use ($defectCategory) {
                        $query->where("wckt.type_code",
                            QueryComparisonOperator::EQUALS,
                            $defectCategory
                        );
                    });
            })
            ->join("wm_workshop_tables wckta",
                function (JoinClause $join) use ($vehicleSys) {
                    $join->on("def.veh_sys",
                        QueryComparisonOperator::EQUALS,
                        "wckta.code")
                        ->where("wckta.type_code",
                            QueryComparisonOperator::EQUALS,
                            $vehicleSys);
                })
            ->where("def.workshop_reference",
                QueryComparisonOperator::EQUALS,
                $details->wshp_act_code)
            ->select(
                "def.id",
                "def.veh_sys",
                "def.defect_id",
                "def.date_def",
                "def.created_at",
                "wckta.description as system_name",
                "def.defect_category_code",
                "wckt.description as defect_category_name",
                "def.defect_code",
                "def.defect_name",
                "def.section_code"
            )->get();

        $comments = WorkShopComment::where("workshop_reference",
            QueryComparisonOperator::EQUALS,
            $details->wshp_act_code)->get();

        $materialsHeader = WorkShopMaterialHeader::where("job_card_no",
            QueryComparisonOperator::EQUALS,
            $reference)->first();

        $materials = $this->workshopRequisitionService
            ->getWorkShopRequisitionItems($reference);

        $services = $this->workshopRequisitionService->getWorkShopRequisitionServiceItems($details->wshp_act_code);

        $nonStock = $this->workshopRequisitionService->getWorkShopRequisitionNonStockItems($details->wshp_act_code);

        $observation = AssessmentObservation::where("reference",
            QueryComparisonOperator::EQUALS,
            $details->wshp_act_code)->get();

        $pettyCashItems = $this->workshopRequisitionService->getPettyCashItems($reference);

        $materials = $materials->merge($nonStock);

        $labour = DB::table('wm_workshop_labours labour')
            ->where("wshp_act_code",
                QueryComparisonOperator::EQUALS,
                $details->wshp_act_code)
            ->join('wm_workshop_tables defect',
                'labour.defect_id',
                QueryComparisonOperator::EQUALS,
                'defect.id')
            ->select('labour.*', 'defect.description as defect_name')
            ->get();

        return array(
            $accessoriesCheckedIn,
            $details,
            $officeDetails,
            $defects,
            $comments,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation);
    }

}
