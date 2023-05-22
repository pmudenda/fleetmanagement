<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\configurations\WorkShop;
use App\Models\general\CostCenters;
use App\Models\reference\Areas;
use Illuminate\Http\JsonResponse;
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
            $businessAreas = Areas::get();
        } catch (\Exception $e) {
            Log::error($e);
        }

        $costCenters = CostCenters::orderBy('description')->get();
        return view('modules.workshopManagement.index')
            ->with(compact(
                'workshopsList',
                'costCenters',
                'businessAreas'
            ));
    }

    public function sections(): View
    {
        $type = ConfigurationTypes::WORK_SHOP_SECTION;
        $typeStr = $type;
        $workshop_sections = GeneralTableConfigurations::where(Constants::TYPE_KEY, $type)->get();

        return view('modules.workshopManagement.sections')
            ->with(compact(
                'workshop_sections',
                'type',
                'typeStr'
            ));
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
        $workshopsList = GeneralTableConfigurations::where('type', '=', ConfigurationTypes::WORK_SHOP_SECTION->value)
            ->where('active', 1)
            ->get();

        return response()->json([
            'state' => 'success',
            'message' => 'Data retrieved Successfully',
            'payload' => $workshopsList
        ]);
    }

}
