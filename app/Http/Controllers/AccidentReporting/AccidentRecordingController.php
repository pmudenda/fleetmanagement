<?php

namespace App\Http\Controllers\AccidentReporting;

use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccidentRecordingRequest;
use App\Models\Accident;
use App\Models\Settings\GeneralTable;
use App\Services\FileUploads\FileUploadService;
use App\Services\Workflow\DocumentNumberGenerationService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $minDate = Carbon::now()->subtract('year', 10);

        return view("modules.accidentReporting.create")
            ->with(compact(
                'minDate'
            ));
    }

    public function store(AccidentRecordingRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $reference = $this->numberGeneratorService->generateReferenceNumber('ACC_RPT');
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

            if ($request->hasFile('accident_rpt')) {
                $this->fileUploadService->uploadFile($request,
                    'accident_rpt',
                    'VehicleAccident',
                    $reference,
                    'Police Report',
                    'Report',
                    $user
                );
            }

            return response()->json([
                'state' => 'success',
                'message' => 'Successfully Recorded An Accident',
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
