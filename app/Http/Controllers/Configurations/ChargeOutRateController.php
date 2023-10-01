<?php

namespace App\Http\Controllers\Configurations;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeOutRateRequest;
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

            Log::debug("Brand Code " . $brand);
            Log::debug("Model Code " . $model);
            Log::debug("Body Type Code " . $bodyType);
            Log::debug("Charge " . $charge);

            $make = VehicleBrand::where('code', '=', $brand)->first();
            $modelType = VehicleModel::where('code', '=', $model)
                ->where('brand_code', '=', $brand)
                ->where('body_type_code', '=', $bodyType)
                ->first();

            $type = VehicleBodyType::where('code', '=', $bodyType)->first();
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
                [
                    'message' => SystemMessages::chargeOutRateAddedSuccessfully(),
                    'success' => true,
                    'payload' => $request->all()
                ]
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'message' => ErrorMessages::getMessage('err_0005'),
                    'success' => false,
                    'payload' => $request->all()
                ]
            );
        }

    }
}
