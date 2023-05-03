<?php

namespace App\Http\Controllers\Requisitions;

use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\general\CostCenters;
use App\Models\MaterialHeader;
use App\Models\RequisitionTypes;
use App\Services\Requisitions\FuelRequisitionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FuelRequisitionController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $requisitions = MaterialHeader::get();
        return view("modules.requisitions.fuel.list")
            ->with(compact('requisitions'));
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user();
        $costCenter = CostCenters::where('code_cost_center', $user->cc_code)->first();
        $requisitionTypes = RequisitionTypes::where('status', '01')->where('module', 'FR')->get();
        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        return view('modules.requisitions.fuel.create')
            ->with(compact('user', 'requisitionTypes', 'costCenter', 'daysToNextRefuel'));
    }

    public function store(FuelRequisitionPostRequest $request): JsonResponse
    {
        try {
            if ($request->get('fuel_allocation') < $request->get('material_quantity')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity requested can not be more than allocation'
                ]);
            }
            $requisitionService = new FuelRequisitionService();
            return $requisitionService->processRequest($request);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'We could not complete processing your request due to an error'
            ]);
        }
    }

    public function show(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $req_no = $request->get('ref');

        $user = Auth::user();
        $requestDetails = DB::table('GEN_MATERIAL_HEADERS')->
        where('GEN_MATERIAL_HEADERS.req_no', $req_no)
            ->join('GEN_MATERIAL_DETAILS', 'GEN_MATERIAL_HEADERS.req_no', '=', 'GEN_MATERIAL_DETAILS.req_no')
            ->leftJoin('CONFIG_STATUSES', 'GEN_MATERIAL_HEADERS.status', '=', 'CONFIG_STATUSES.code')
            ->select('GEN_MATERIAL_HEADERS.*', 'GEN_MATERIAL_DETAILS.*', 'CONFIG_STATUSES.name as status_name', 'CONFIG_STATUSES.color_code')
            ->first();
        $costCenter = CostCenters::where('code_cost_center', $user->cc_code)->first();
        $requisitionTypes = RequisitionTypes::where('status', '01')->where('module', 'FR')->get();
        $daysToNextRefuel = config('settings.fuel_requisition_validity');
        $approvalHistory = [];

        return view('modules.requisitions.fuel.show')
            ->with(compact(
                'user',
                'requisitionTypes',
                'costCenter',
                'requestDetails',
                'daysToNextRefuel',
                'approvalHistory'
            ));
    }
}
