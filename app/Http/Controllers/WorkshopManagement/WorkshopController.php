<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Enums\Constants;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\configurations\WorkShop;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WorkshopController extends Controller
{
    public function index(): View
    {
        $workshopsList = WorkShop::get();
        return view('modules.workshopManagement.index')
            ->with(compact('workshopsList'));
    }

    public function sections(): View
    {
        $type = 'wkshp_section';
        $typeStr = $type;
        $workshop_sections = GeneralTableConfigurations::where(Constants::TYPE_KEY, 'wkshp_section')->get();

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

}
