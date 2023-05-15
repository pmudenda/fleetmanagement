<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function create(): View
    {

        $daysToNextRefuel = 0;
        $requisitionTypes = [];
        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'requisitionTypes',
                    'daysToNextRefuel'
                )
            );
    }
}
