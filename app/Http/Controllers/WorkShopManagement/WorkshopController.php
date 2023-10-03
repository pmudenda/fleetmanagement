<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\QueryComparisonOperator;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Enums\Modules;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Common\CostCenter;
use App\Models\Reference\Area;
use App\Models\Settings\GeneralTable;
use App\Models\Settings\WorkShop;
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
                ->leftJoin('CONFIG_STATUSES',
                    'GEN_MATERIAL_HEADERS.status',
                    QueryComparisonOperator::EQUALS,
                    'CONFIG_STATUSES.code')
                ->leftJoin('SEC_USERS',
                    'GEN_MATERIAL_HEADERS.requested_by',
                    QueryComparisonOperator::EQUALS,
                    'SEC_USERS.staff_no')
                ->where(
                    'GEN_MATERIAL_HEADERS.requested_by',
                    QueryComparisonOperator::EQUALS,
                    $staff_no)
                ->where('GEN_MATERIAL_HEADERS.IS_FUEL',
                    QueryComparisonOperator::EQUALS,
                    'N')
                ->where('CONFIG_STATUSES.MODULE',
                    QueryComparisonOperator::EQUALS,
                    Modules::MATERIAL->value)
                ->select(
                    'GEN_MATERIAL_HEADERS.*',
                    'SEC_USERS.name as originator',
                    'CONFIG_STATUSES.name as status_name')
                ->orderBy('GEN_MATERIAL_HEADERS.created_at', 'desc')
                ->get();
        } else {
            return DB::table('GEN_MATERIAL_HEADERS')
                ->leftJoin('CONFIG_STATUSES',
                    'GEN_MATERIAL_HEADERS.status',
                    QueryComparisonOperator::EQUALS,
                    'CONFIG_STATUSES.code')
                ->leftJoin('SEC_USERS',
                    'GEN_MATERIAL_HEADERS.requested_by',
                    QueryComparisonOperator::EQUALS,
                    'SEC_USERS.staff_no')
                ->where(
                    'GEN_MATERIAL_HEADERS.IS_FUEL',
                    QueryComparisonOperator::EQUALS,
                    'N')
                ->where('CONFIG_STATUSES.MODULE',
                    QueryComparisonOperator::EQUALS,
                    Modules::MATERIAL->value)
                ->select(
                    'GEN_MATERIAL_HEADERS.*',
                    'SEC_USERS.name as originator',
                    'CONFIG_STATUSES.name as status_name')
                ->orderBy('GEN_MATERIAL_HEADERS.created_at', 'desc')
                ->get();
        }

    }

    public function requisitions(): View|Application
    {
        $requisitions = self::getMyRequisitions(
            null,
            'N'
        );

        $requisition_type = "WORKSHOP";
        return view("modules.workshopManagement.list")
            ->with(
                compact(
                    'requisitions',
                    'requisition_type'
                )
            );
    }

    public function sections(): View
    {
        $type = ConfigurationTypes::WORK_SHOP_SECTION->value;
        $workshopSections = GeneralTable::where(Constants::TYPE_KEY, $type)->get();

        return view('modules.configurations.generalTables.index')->with(
            [
                'title' => "Workshop Sections",
                'entries' => $workshopSections,
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
        $workshopsList = GeneralTable::where('type',
            QueryComparisonOperator::EQUALS,
            ConfigurationTypes::WORK_SHOP_SECTION->value)
            ->where('active', 1)
            ->get();

        return response()->json([
            'state' => 'success',
            'message' => 'Data retrieved Successfully',
            'payload' => $workshopsList
        ]);
    }

}
