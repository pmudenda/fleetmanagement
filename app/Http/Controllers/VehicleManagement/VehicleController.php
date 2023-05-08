<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Http\Controllers\Controller;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\VehicleManagement\OnBoarding\OnBoardingService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    private OnBoardingService $onBoardingService;

    public function __construct(OnBoardingService $onBoardingService)
    {
        $this->onBoardingService = $onBoardingService;
    }

    public function getAllDetails($ref): JsonResponse
    {
        try {
            if (empty($ref)) {
                return response()->json([
                    'success' => 'false',
                    'statusDescription' => 'Bad Request',
                    'message' => 'Missing required parameter'
                ]);
            }

            $vehicle = $this->onBoardingService->getVehicleDetails($ref);
            $vehicleDocuments = $this->onBoardingService->getVehicleDocuments($ref);

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'documents' => $vehicleDocuments
                ],
                'success' => !empty($vehicle),
                'message' => 'Vehicle Details retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => 'false',
                'message' => 'We could not complete processing your request, Please contact System Administrator'
            ]);
        }
    }

    public function getDetails(Request $request): JsonResponse
    {
        try {
            if (empty($request->vehicle_registration)) {
                return response()->json([
                    'success' => 'false',
                    'statusDescription' => 'Bad Request',
                    'message' => 'Missing required parameter'
                ]);
            }

            // determine material type in form of fuel
            $vehicle = DB::table('VM_VEHICLE_HEADER')->
            where('registration_number', $request->vehicle_registration)
                //->where('on_boarding_status', $request->vehicle_registration)
                ->leftJoin('VM_ASSIGNMENTS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_ENGINE_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ENGINE_DETAILS.vehicle_header_id')
                ->select('VM_VEHICLE_HEADER.*', 'VM_ASSIGNMENTS.*', 'VM_ENGINE_DETAILS.fuel_allocation', 'VM_ENGINE_DETAILS.fuel_types')
                ->first();

            if (!$vehicle) {
                return response()->json([
                    'success' => 'false',
                    'statusDescription' => 'Not Found',
                    'message' => 'Vehicle not found'
                ]);
            }

            $article = DB::table('GEN_ARTICLES')
                ->leftJoin('CONFIG_UNIT_OF_MEASURES', 'GEN_ARTICLES.unit_of_measure_code', '=', 'CONFIG_UNIT_OF_MEASURES.code')
                ->where('GEN_ARTICLES.code', '=', $vehicle->fuel_types)
                ->select('GEN_ARTICLES.*', 'CONFIG_UNIT_OF_MEASURES.name as unitName', 'CONFIG_UNIT_OF_MEASURES.short_name')
                ->first();

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'article' => $article
                ],
                'success' => !empty($vehicle),
                'message' => 'Vehicle Details retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => 'false',
                'message' => 'We could not complete processing your request, Please contact System Administrator'
            ]);
        }
    }

    public function list(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $vehicleList = VehicleHeader::get();
        return view('vehicleManagement.vehicleList')
            ->with(compact('vehicleList'));
    }
}
