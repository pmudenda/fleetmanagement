<?php

namespace App\Http\Controllers\Configurations;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeOutRateRequest;
use App\Models\ChargeOutRate;
use App\Models\Settings\vehicle\VehicleBodyType;
use App\Models\Settings\vehicle\VehicleBrand;
use App\Models\Settings\vehicle\VehicleModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ChargeOutRateController extends Controller
{

    public function index(): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
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
            $type = $request->get("bodyType");
            $charge = $request->get("rate");
            $make = VehicleBrand::where('id', '=', $brand)->first();
            $model_type = VehicleModel::where('id', '=', $model)->first();
            $type = VehicleBodyType::where('id', '=', $type)->first();

            ChargeOutRate::create([
                'vehicle_specification' => $type->id . $make->id . $model_type->id,
                'vehicle_description' => $type->name . ' ' . $make->name . ' ' . $model_type->model_name . ' ' . $model_type->model_code,
                'charge' => $charge,
                'created_by' => auth()->user()->id
            ]);
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
