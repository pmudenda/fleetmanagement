<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Http\Controllers\Controller;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\VehicleManagement\VehicleDetailsService;
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
    private VehicleDetailsService $vehicleDetailsService;

    public function __construct(VehicleDetailsService $vehicleDetailsService)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
    }

    public function getAllDetails(Request $request, $ref): JsonResponse
    {

        try {
            if (empty($ref) && !$request->has('reference')) {
                return response()->json([
                    'success' => 'false',
                    'statusDescription' => 'Bad Request',
                    'message' => 'Missing required parameter'
                ]);
            }

            $ref = $request->get('reference');

            $vehicle = null;
            $vehicleDocuments = null;

            Log::info('reference is ' . $ref);
            if ($ref != 0) {
                $vehicle = $this->vehicleDetailsService->getVehicleDetails($ref)->first();
                $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($ref);
            }

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'documents' => $vehicleDocuments
                ],
                'success' => !empty($vehicle),
                'message' => !empty($vehicle) ? 'Vehicle Details retrieved successfully'
                    : 'Could not read vehicle details'
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
            $vehicle = $this->vehicleDetailsService->getBasicVehicleDetails($request->vehicle_registration);

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
