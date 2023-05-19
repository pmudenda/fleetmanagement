<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\configurations\ConfigAccessories;
use App\Models\RepairTypes;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function create(): View
    {

        $daysToNextRefuel = 0;
        $repairTypes = RepairTypes::get();
        $accessories = ConfigAccessories::where('status', '=', StatusHelper::active())->get();
        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes',
                    'accessories'
                )
            );
    }
}
