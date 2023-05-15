<?php

namespace App\Http\Controllers;

class MaintenanceController extends Controller
{
    public function create(): string
    {
        return view('modules.requisition.maintenance.create');
    }
}
