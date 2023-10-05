<?php

namespace App\Http\Controllers\Configurations;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Enums\ResponseState;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeOutRateRequest;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Settings\vehicle\VehicleBodyType;
use App\Models\Settings\vehicle\VehicleBrand;
use App\Models\Settings\vehicle\VehicleModel;
use App\Models\VehicleManagement\ChargeOutRate;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChargeOutRateController extends Controller
{

    public function create(): View|Application
    {
        $chargeOutRateList = ChargeOutRate::get();
        return view('modules.configurations.chargeoutrate')
            ->with(compact(
                'chargeOutRateList'
            ));
    }

    public function store(ChargeOutRateRequest $request): JsonResponse
    {
        try {
            $brand = $request->get("brand");
            $model = $request->get("model");
            $bodyType = $request->get("bodyType");
            $charge = floatval($request->get("rate"));

            $make = VehicleBrand::where('code',
                QueryComparisonOperator::EQUALS,
                $brand)->first();
            $modelType = VehicleModel::where('code',
                QueryComparisonOperator::EQUALS,
                $model)
                ->where('brand_code',
                    QueryComparisonOperator::EQUALS,
                    $brand)
                ->where('body_type_code',
                    QueryComparisonOperator::EQUALS,
                    $bodyType)
                ->first();

            $type = VehicleBodyType::where('code',
                QueryComparisonOperator::EQUALS,
                $bodyType)->first();
            $user = Auth::user();

            DB::beginTransaction();
            ChargeOutRate::create([
                'vehicle_specification' => "$type->code$make->code$modelType->code",
                'vehicle_description' => $type->name
                    . ' ' . $make->name
                    . ' ' . $modelType->model_name
                    . ' ' . $modelType->model_code,
                'charge' => $charge,
                'currency' => 'k',
                'created_by' => $user->staff_no
            ]);

            DB::commit();

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::chargeOutRateAddedSuccessfully(),
                    $request->all()
                )
            );

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    ErrorMessages::getMessage('err_0005'),
                    $request->all()
                )
            );
        }

    }
}
