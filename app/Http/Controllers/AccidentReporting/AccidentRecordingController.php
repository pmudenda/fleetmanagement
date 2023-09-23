<?php

namespace App\Http\Controllers\AccidentReporting;

use App\Constants\WorkflowModules;
use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccidentRecordingRequest;
use App\Models\Accident;
use App\Models\Common\File;
use App\Models\Settings\GeneralTable;
use App\Services\FileUploads\FileUploadService;
use App\Services\Workflow\DocumentNumberGenerationService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AccidentRecordingController extends Controller
{
    private readonly FileUploadService $fileUploadService;

    private DocumentNumberGenerationService $numberGeneratorService;

    public function __construct(DocumentNumberGenerationService $numberGeneratorService,
                                FileUploadService               $fileUploadService)
    {
        $this->numberGeneratorService = $numberGeneratorService;
        $this->fileUploadService = $fileUploadService;
    }

    public function create(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $minDate = Carbon::now()->subtract('year', 10);

        return view("modules.accidentReporting.create")
            ->with(compact(
                'minDate'
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

        return view("modules.accidentReporting.show")
            ->with(compact(
                'accident',
                'minDate',
                'files'
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
            $user = auth()->user();

            DB::beginTransaction();
            $reference = $this->numberGeneratorService->generateReferenceNumber(
                WorkflowModules::ACCIDENT_REPORT);

            Accident::create([
                'reported_by' => $user->staff_no,
                'reference' => $reference,
                'area' => $request->get('area'),
                'vehicle_reg_no' => $request->get('registrationNo'),
                'driver' => $request->get('driver_staff_number'),
                'date_of_accident' => Carbon::parse($request->validated('date')),
                'time_of_accident' => Carbon::parse($request->validated('time')),
                'date_reported' => Carbon::now()->format('Y-m-d'),
                'time_reported' => Carbon::now(),
                'nature_of_accident' => $request->get('accidentNature'),
                'type_of_accident' => $request->get('accidentType'),
                'guilty' => $request->get('guilty'),
                'location' => $request->get('location'),
                'death' => $request->get('death'),
                'num_passengers' => $request->validated('num_passengers'),
                'mileage' => $request->validated('mileage'),
                'other_people_involved' => $request->validated('other_people_involved'),
                'day_of_week' => $request->validated('day_of_week'),
                'other_vehicle_involved' => $request->validated('other_vehicle_involved'),
                'property' => $request->validated('property'),
                'vehicle_insured' => $request->validated('insured'),
                'driver_experience' => $request->validated('experience'),
            ]);

            if ($request->hasFile('insurance_report')) {
                $this->fileUploadService->uploadFile(
                    $request,
                    'insurance_report',
                    'VehicleAccident',
                    $reference,
                    'Insurance Report',
                    'Report',
                    $user
                );
            }

            if ($request->hasFile('police_report')) {
                $this->fileUploadService->uploadFile(
                    $request,
                    'police_report',
                    'VehicleAccident',
                    $reference,
                    'Police Report',
                    'Report',
                    $user
                );
            }

            if ($request->hasFile('attachment')) {
                $this->fileUploadService->uploadFile(
                    $request,
                    'attachment',
                    'VehicleAccident',
                    $reference,
                    'Accident Pictures',
                    'Accident Pictures',
                    $user
                );
            }

            DB::commit();

            return response()->json([
                'state' => 'success',
                'redirectUrl' => URL::signedRoute('accident.show', [
                    'reference' => $reference
                ]),
                'message' => 'Accident Successfully Recorded An \n Reference' . $reference,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => 'Failed To record Recorded An Accident',
            ]);
        }

    }

    public function getAccidentTypes(): JsonResponse
    {
        try {
            $data = GeneralTable::where('type', '=', ConfigurationTypes::ACCIDENT_TYPES->value)->get();

            return response()->json([
                'state' => 'successful',
                'payload' => $data,
            ]);
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json([
                'state' => 'failure',
                'payload' => [],

            ]);
        }
    }

    public function getAccidentNatures(): JsonResponse
    {
        try {
            $data = GeneralTable::where('type', '=', ConfigurationTypes::ACCIDENT_NATURE->value)->get();
            return response()->json([
                'state' => 'successful',
                'payload' => $data,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'state' => 'failure',
                'payload' => [],

            ]);
        }
    }
}
