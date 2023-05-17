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
            $vehicle_state = '';
            // StatusHelper::onboardingComplete()
            if ($vehicle->on_boarding_status != "030") {
                $vehicle_state = str_replace("@", $vehicle->on_boarding_status, "Pending @ detail processing");
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
    public function getArticleByCode($ref_code): mixed
    {
        $results = DB::table('SPMS_ARTICLES_VIEW')
            ->leftJoin('STOCK_MANAGEMENT_VIEW', 'SPMS_ARTICLES_VIEW.CODE_ARTICLE', '=', 'STOCK_MANAGEMENT_VIEW.CODE_ARTICLE')
            ->leftJoin('UNITS_VIEW', 'SPMS_ARTICLES_VIEW.UNIT_MEASURE', '=', 'UNITS_VIEW.code_unit')
            ->where('STOCK_MANAGEMENT_VIEW.LEVEL_TYPE', '=', '02')
            ->where('SPMS_ARTICLES_VIEW.CODE_ARTICLE', '=', $ref_code)
            ->select(
                'UNITS_VIEW.description',
                'SPMS_ARTICLES_VIEW.description as name',
                'SPMS_ARTICLES_VIEW.CODE_ARTICLE as code',
                'STOCK_MANAGEMENT_VIEW.PRICE_MAP as price'
            )
            ->get();

        return $results->first();
    }


    public function cleanUpWindow(Request $request): View
    {
        return view('vehicleManagement.migration.index');
    }

    public function register(Request $request): View
    {
        return view('vehicleManagement.vehicleList');
    }
}
