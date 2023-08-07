<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Enums\Modules;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Settings\GeneralTableConfiguration;
use App\Models\Settings\WorkShop;
use App\Models\Common\CostCenter;
use App\Models\Reference\Area;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class WorkshopController extends Controller
{
    public function index(): View
    {
        $workshopsList = [];
        try {
            $workshopsList = WorkShop::get();
        } catch (\Exception $e) {
            Log::error($e);
        }
        $businessAreas = [];
        try {
            $businessAreas = Area::get();
        } catch (\Exception $e) {
            Log::error($e);
        }

        $costCenters = CostCenter::orderBy('description')->get();
        return view('modules.workshopManagement.index')
            ->with(compact(
                'workshopsList',
                'costCenters',
                'businessAreas'
            ));
    }


    public function getMyRequisitions($staff_no): Collection
    {
        if ($staff_no) {
            return DB::table('GEN_MATERIAL_HEADERS')
                //->leftJoin('GEN_MATERIAL_DETAILS', 'GEN_MATERIAL_HEADERS.req_no', '=', 'GEN_MATERIAL_DETAILS.req_no')
                ->leftJoin('CONFIG_STATUSES', 'GEN_MATERIAL_HEADERS.status', '=', 'CONFIG_STATUSES.code')
                ->leftJoin('SEC_USERS', 'GEN_MATERIAL_HEADERS.requested_by', '=', 'SEC_USERS.staff_no')
                ->where('GEN_MATERIAL_HEADERS.requested_by', '=', $staff_no)
                ->where('GEN_MATERIAL_HEADERS.IS_FUEL', '=', 'N')
                ->where('CONFIG_STATUSES.MODULE', '=', Modules::Material)
                ->select(
                    'GEN_MATERIAL_HEADERS.*',
                    //'GEN_MATERIAL_DETAILS.quantity',
                    //'GEN_MATERIAL_DETAILS.quantity_issued',
                    'SEC_USERS.name as originator',
                    'CONFIG_STATUSES.name as status_name')
                ->orderBy('GEN_MATERIAL_HEADERS.created_at', 'desc')
                ->get();
        } else {
            return DB::table('GEN_MATERIAL_HEADERS')
                //->leftJoin('GEN_MATERIAL_DETAILS', 'GEN_MATERIAL_HEADERS.req_no', '=', 'GEN_MATERIAL_DETAILS.req_no')
                ->leftJoin('CONFIG_STATUSES', 'GEN_MATERIAL_HEADERS.status', '=', 'CONFIG_STATUSES.code')
                ->leftJoin('SEC_USERS', 'GEN_MATERIAL_HEADERS.requested_by', '=', 'SEC_USERS.staff_no')
                ->where('GEN_MATERIAL_HEADERS.IS_FUEL', '=', 'N')
                ->where('CONFIG_STATUSES.MODULE', '=', Modules::Material)
                ->select(
                    'GEN_MATERIAL_HEADERS.*',
                    //'GEN_MATERIAL_DETAILS.quantity',
                    //'GEN_MATERIAL_DETAILS.quantity_issued',
                    'SEC_USERS.name as originator',
                    'CONFIG_STATUSES.name as status_name')
                ->orderBy('GEN_MATERIAL_HEADERS.created_at', 'desc')
                ->get();
        }

    }

    public function requisitions(): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $staff_no = auth()->user()->staff_no;
        $requisitions = self::getMyRequisitions(null, 'N');

        $requisition_type = "WORKSHOP";
        return view("modules.workshopManagement.list")
            ->with(compact('requisitions',
                'requisition_type'));
    }

    public function sections(): View
    {
        $type = ConfigurationTypes::WORK_SHOP_SECTION;
        $typeStr = $type;
        $workshop_sections = GeneralTableConfiguration::where(Constants::TYPE_KEY, $type)->get();

       /* return view('modules.workshopManagement.sections')
            ->with(compact(
                'workshop_sections',
                'type',
                'typeStr'
            ));*/

        return view('modules.configurations.generalTables.index')->with(
            [
                'title' => "Workshop Sections",
                'entries' => $workshop_sections,
                'type' => $type,
                'statusList' => []
            ]);
    }

    public function getActiveWorkShops(): JsonResponse
    {
        $workshopsList = WorkShop::where('status', '=', StatusHelper::active())->get();

        return response()->json([
            'state' => 'success',
            'message' => 'Data retrieved Successfully',
            'payload' => $workshopsList
        ]);
    }

    public function getActiveWorkShopSections(): JsonResponse
    {
        $workshopsList = GeneralTableConfiguration::where('type', '=', ConfigurationTypes::WORK_SHOP_SECTION->value)
            ->where('active', 1)
            ->get();

        return response()->json([
            'state' => 'success',
            'message' => 'Data retrieved Successfully',
            'payload' => $workshopsList
        ]);
    }

}
