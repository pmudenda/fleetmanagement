<?php

namespace App\Http\Controllers\Requisitions;

use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\general\CostCenters;
use App\Models\RequisitionTypes;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
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
    public function store(FuelRequisitionPostRequest $request): JsonResponse
    {
        $validator = null;

        if ($request->get('CostAssignedTo') == 'CostCenterBasedRequisition') {
            $validator = Validator::make($request->all(), [
                'cost_center_name' => 'required|max:255',
                'cost_centre_code' => 'required',
            ])->validate();
        } else if ($request->get('CostAssignedTo') == 'ProjectBasedRequisition') {
            $validator = Validator::make($request->all(), [
                'project_code' => 'required',
            ])->validate();
        }

        if ($validator && $validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Some Fields Failed Data Validation Failed',
                'errors' => $validator->errors()
            ]);
        }

        // if requisition of is out of town
        if ($request->get('requisition_type') == '011') {
            $validator = Validator::make($request->all(), [
                'departure_date' => 'required|max:255',
                'return_date' => 'required',
            ])->validate();
        }

        if ($validator && $validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Some Fields Failed Data Validation Failed',
                'errors' => $validator->errors()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Requisition  Submitted Successfully..'
        ]);
    }
}
