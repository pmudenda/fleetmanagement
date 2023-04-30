<?php

namespace App\Http\Controllers\Requisitions;

use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\general\CostCenters;
use App\Models\RequisitionTypes;
use App\Services\Requestions\FuelRequisitionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    public function store(FuelRequisitionPostRequest $request): JsonResponse
    {
        if ($request->get('fuel_allocation') > $request->get('material_quantity')) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity requested can not be more than allocation'
            ]);
        }

        $requisitionService = new FuelRequisitionService();
        return $requisitionService->processRequest($request);
    }
}
