<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\ResponseState;
use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Settings\GeneralTable;
use App\Services\VehicleManagement\InsuranceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class InsuranceController extends Controller
{
    private InsuranceService $insuranceService;

    public function __construct(InsuranceService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
    }

    public function create(): View
    {
        $insuranceSubTypes = GeneralTable::where(
            'type',
            QueryComparisonOperator::EQUALS,
            ConfigurationTypes::INSURANCE_SUB_TYPES->value
        )->get();
        return view('modules.vehicleManagement.insurance.create')
            ->with(compact('insuranceSubTypes'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->insuranceService->save($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::INSURANCE_RECORD_SAVED
                ));
        } catch (Exception $e) {
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            }

            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message
                )
            );
        }
    }
}
