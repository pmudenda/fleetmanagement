<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\VehicleManagement\VehicleDetailsService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    private VehicleDetailsService $vehicleDetailsService;

    public function __construct(VehicleDetailsService $vehicleDetailsService)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
    }

    public function getAllDetails(Request $request): JsonResponse
    {
        try {
            if (empty($request->has('reference'))) {
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
                $vehicle = $this->vehicleDetailsService->getVehicleDetails($ref);
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
            $vehicleImages = $this->vehicleDetailsService->getVehicleImages($vehicle->vehicle_header_id);

            $article = $this->getArticleByCode($vehicle->fuel_types);
            $vehicle_state = "Pending @i detail processing";
            // StatusHelper::onboardingComplete()
            if($vehicle->on_boarding_status == "021"){
                $vehicle_state = str_replace('@i', 'General Data', $vehicle_state );
            }
            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'article' => $article,
                    'images' => $vehicleImages,
                    'vehicle_state' => $vehicle_state
                ],
                'success' => !empty($vehicle),
                'message' => 'Vehicle Details retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => 'false',
                'message' => 'We could not complete processing your request, Please Fleet Master System Administrator '
            ]);
        }
    }

    public function list(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $vehicleList = VehicleHeader::get();
        return view('vehicleManagement.vehicleList')
            ->with(compact('vehicleList'));
    }

    /**
     * @param $ref_code
     * @return Collection
     */
    public function getArticleByCode($ref_code)
    {
        $results = DB::table('GEN_ARTICLES')
            ->leftJoin('CONFIG_UNIT_OF_MEASURES', 'GEN_ARTICLES.unit_of_measure_code', '=', 'CONFIG_UNIT_OF_MEASURES.code')
            ->where('GEN_ARTICLES.code', '=', $ref_code)
            ->select('GEN_ARTICLES.*', 'CONFIG_UNIT_OF_MEASURES.name as unitName', 'CONFIG_UNIT_OF_MEASURES.short_name')
            ->get();

        return $results->first();
    }
}
