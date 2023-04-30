<?php

namespace App\Http\Controllers\Requisitions;

use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\general\CostCenters;
use App\Models\RequisitionTypes;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FuelRequisitionController extends Controller
{
    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user();
        $costCenter = CostCenters::where('code_cost_center', $user->cc_code)->first();
        $requisitionTypes = RequisitionTypes::where('status', '01')->where('module', 'FR')->get();
        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        return view('modules.requisitions.fuel')
            ->with(compact('user', 'requisitionTypes', 'costCenter', 'daysToNextRefuel'));
    }

    /**
     * @throws ValidationException
     */
    public function store(FuelRequisitionPostRequest $request): RedirectResponse
    {
        if ($request->get('CostAssignedTo') == 'CostCenterBasedRequisition') {
            $validator = Validator::make($request->all(), [
                'cost_center_name' => 'required|unique:posts|max:255',
                'cost_centre_code' => 'required',
            ])->validate();
        } else if ($request->get('CostAssignedTo') == 'ProjectBasedRequisition') {
            Validator::make($request->all(), [
                'project_code' => 'required',
            ])->validate();
        }

        return redirect()->route('new.fuel.requisition')->with('message', 'Requisition  Submitted Successfully..');
    }
}
