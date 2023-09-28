<?php

namespace App\Http\Controllers\AccidentReporting;

use App\Constants\QueryComparisonOperator;
use App\Enums\ConfigurationTypes;
use App\Exceptions\DuplicateDataException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccidentRecordingRequest;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Accident;
use App\Models\Common\File;
use App\Models\Settings\GeneralTable;
use App\Services\Accidents\AccidentService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AccidentRecordingController extends Controller
{

    private AccidentService $accidentService;

    public function __construct(AccidentService $accidentService)
    {
        $this->accidentService = $accidentService;
    }

    public function create(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $minDate = Carbon::now()->subtract('year', 10);
        $daysOfWeek = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday",
        ];
        return view("modules.accidentReporting.create")
            ->with(compact(
                'minDate',
                'daysOfWeek'
            ));
    }

    public function show(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $minDate = Carbon::now()->subtract('year', 10);

        $accident = Accident::where('reference', '=', $request->get('reference'))
            ->first();

        $files = File::where('reference_number', '=', $request->get('reference'))
            ->get();

        $daysOfWeek = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday",
        ];

        return view("modules.accidentReporting.show")
            ->with(compact(
                'accident',
                'minDate',
                'files',
                'daysOfWeek'
            ));
    }

    public function list(Request $request): View
    {
        $accidents = Accident::paginate(100);
        return view("modules.accidentReporting.list")
            ->with(compact(
                'accidents',
            ));
    }

    public function store(AccidentRecordingRequest $request): JsonResponse
    {
        try {

            $reference = $this->accidentService->saveAccidentReport($request);

            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    'Accident Successfully Recorded An Reference ' . $reference,
                    [],
                    URL::signedRoute('accident.show', [
                        'reference' => $reference
                    ])
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            $message = 'Failed To record Recorded An Accident';
            if ($e instanceof DuplicateDataException) {
                $message = $e->getMessage();
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $message
                )
            );
        }

    }

    public function getAccidentTypes(): JsonResponse
    {
        try {
            $data = GeneralTable::where('type', '=', ConfigurationTypes::ACCIDENT_TYPES->value)->get();

            return response()->json(
                FleetMasterJsonResponse::response(
                    'successful',
                    true,
                    '',
                    $data
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    '',
                    []
                )
            );
        }
    }

    public function getAccidentNatures(): JsonResponse
    {
        try {
            $data = GeneralTable::where('type',
                QueryComparisonOperator::EQUALS,
                ConfigurationTypes::ACCIDENT_NATURE->value)->get();
            return response()->json(
                FleetMasterJsonResponse::response(
                    'successful',
                    true,
                    '',
                    $data
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    '',
                    []
                )
            );
        }
    }

    public function getLatestAccidentReport(Request $request): JsonResponse
    {
        try {
            $data = Accident::where('vehicle_reg_no',
                QueryComparisonOperator::EQUALS,
                $request->get('vehicleRegistration'))
                ->orderBy('created_at', 'desc')
                ->first();

            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    '',
                    $data
                )
            );
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    '',
                    []
                )
            );
        }
    }
}
