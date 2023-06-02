<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\configurations\ConfigAccessories;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    private WorkshopService $workshopService;
    private DocumentNumberGenerationService $numberGeneratorService;

    public function __construct(WorkshopService $workshopService, DocumentNumberGenerationService $numberGeneratorService)
    {
        $this->workshopService = $workshopService;
        $this->numberGeneratorService = $numberGeneratorService;
    }

    public function create(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $repairTypes = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)->get();

        //$accessories = ConfigAccessories::where('status', '=', StatusHelper::active())->get();

        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes'
                )
            );
    }

    public function step2(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        if ($request->has('step') && $request->get('step') != "2") {
            abort(401);
        }
        if (!$request->has('step')) {
            return redirect(URL::signedRoute('maintenance.requisition', ['step' => 1]));
        }

        $step = $request->get('step') ?? 0;
        $reference = $request->get('reference');

        $repairTypes = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)->get();

        $accessories = ConfigAccessories::where('status', '=', StatusHelper::active())->get();

        $details = JobCardHeader::where('job_card_no', '=', $reference)->orderBy('id', 'desc')->first();
        /*$query  = DB::table('WKS_JOB_CARD_HEADER')
            ->leftJoin('SEC_USERS', 'WKS_JOB_CARD_HEADER.received_by', '=', 'SEC_USERS.staff_no')
            ->leftJoin('CONFIG_GENERAL_TABLES', 'WKS_JOB_CARD_HEADER.receiving_section', '=', 'CONFIG_GENERAL_TABLES.code')
            ->where('CONFIG_GENERAL_TABLES.type', ConfigurationTypes::WORK_SHOP_SECTION)
            ->select('WKS_JOB_CARD_HEADER.*', 'CONFIG_GENERAL_TABLES.name as section_in_name', 'SEC_USERS.name as service_advisor')
            ->get();
        $details = $query->first();*/
        return view('modules.requisitions.maintenance.step2')
            ->with(
                compact(
                    'repairTypes',
                    'accessories',
                    'details'
                )
            );
    }

    public function getFuelLevels(): JsonResponse
    {
        $fuel_levels = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::FUEL_LEVELS->value)
            ->get();

        return response()->json(
            [
                'state' => 'success',
                'payload' => $fuel_levels
            ]
        );
    }

    public function processJobCard(Request $request): JsonResponse
    {
        try {
            //if ($request->get('modelName') == 'JobCardHeader') {
            $response = $this->workshopService->createJobCard($request);
            return response()->json(
                [
                    'success' => true,
                    'payload' => $response,
                    'redirectUrl' => URL::signedRoute('continue.job.card', ['step' => 2, 'reference' => $response->job_card_no]),
                ]
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'success' => false,
                    'payload' => [],
                    'message' => ErrorMessages::getMessage('err_005')
                ]
            );
        }
    }
}
