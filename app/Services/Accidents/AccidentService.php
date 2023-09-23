<?php

namespace App\Services\Accidents;

use App\Constants\WorkflowModules;
use App\Http\Requests\AccidentRecordingRequest;
use App\Models\Accident;
use App\Services\FileUploads\FileUploadService;
use App\Services\Workflow\DocumentNumberGenerationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccidentService
{
    private readonly FileUploadService $fileUploadService;
    private DocumentNumberGenerationService $numberGeneratorService;

    public function __construct(
        FileUploadService               $fileUploadService,
        DocumentNumberGenerationService $numberGeneratorService
    )
    {
        $this->fileUploadService = $fileUploadService;
        $this->numberGeneratorService = $numberGeneratorService;
    }

    /**
     * @param AccidentRecordingRequest $request
     * @return string
     */
    public function saveAccidentReport(AccidentRecordingRequest $request): string
    {
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
        return $reference;
    }
}
