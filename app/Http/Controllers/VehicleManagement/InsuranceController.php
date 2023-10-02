<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\QueryComparisonOperator;
use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Models\Settings\GeneralTable;
use Illuminate\View\View;

class InsuranceController extends Controller
{
    public function create(): View
    {
        $insuranceSubTypes = GeneralTable::where(
            'module',
            QueryComparisonOperator::EQUALS,
            ConfigurationTypes::INSURANCE_SUB_TYPES->value
        )->get();
        return view('modules.vehicleManagement.insurance.create')
            ->with(compact('insuranceSubTypes'));
    }
}
