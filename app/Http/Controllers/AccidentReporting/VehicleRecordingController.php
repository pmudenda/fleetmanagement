<?php

namespace App\Http\Controllers\AccidentReporting;

use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccidentRecordingRequest;
use App\Models\Accident;
use App\Models\Settings\GeneralTableConfiguration;
use App\Services\FileUploads\FileUploadService;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\WorkshopService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class VehicleRecordingController extends Controller
{
    private readonly FileUploadService $fileUploadService;

    private DocumentNumberGenerationService $numberGeneratorService;

    public function __construct(DocumentNumberGenerationService $numberGeneratorService,
                                FileUploadService               $fileUploadService)
    {
        $this->numberGeneratorService = $numberGeneratorService;
        $this->fileUploadService = $fileUploadService;
    }
    function create(){
        $minDate= Carbon::now()->subtract('year', 10);

        return view("modules.accidentReporting.create")
            ->with(compact(
                'minDate'
            ));
    }

    function store(AccidentRecordingRequest $request)
    {
        try {
            $user = auth()->user();

            $reference = $this->numberGeneratorService->generateReferenceNumber('ACC_RPT');
            Accident::create([
                'reported_by' => $user->staff_no,
                'reference' => $reference,
                'area' => $request->validated('area'),
                'vehicle_reg_no' => $request->validated('registrationNo'),
                'driver' => $request->validated('driver_staff_number'),
                'date_of_accident' => Carbon::parse( $request->validated('date')),
                'time_of_accident' => Carbon::parse($request->validated('time')),
                'date_reported' => Carbon::now()->format('Y-m-d'),
                'time_reported' => Carbon::now(),
                'nature_of_accident' => $request->validated('accidentNature'),
                'type_of_accident' => $request->validated('accidentType'),
                'guilty' => $request->validated('guilty'),
                'location' => $request->validated('location'),
                'death' => $request->validated('death'),
                'num_passengers' => $request->validated('num_passengers'),
                'mileage' => $request->validated('mileage'),
                'other_people_involved' => $request->validated('other_people_involved'),
                'day_of_week' => $request->validated('day_of_week'),
                'other_vehicle_involved' => $request->validated('other_vehicle_involved'),
                'property' => $request->validated('property'),
                'vehicle_insured' => $request->validated('insured'),
                'driver_experience' => $request->validated('experience'),
            ]);

            if($request->hasFile('accident_rpt')) {
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

    function accidentTypes()
    {

        try {
            $data = GeneralTableConfiguration::where('type', '=', ConfigurationTypes::ACCIDENT_TYPES->value)->get();

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
    function accidentNatures()
    {

        try {
            $data = GeneralTableConfiguration::where('type', '=', ConfigurationTypes::ACCIDENT_NATURE->value)->get();
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

   /* function getVehicle($registrationNo){

        try {
            $vehicleData = vehicleDetails::find($registrationNo);
            if (!$vehicleData){
                $response = [
                    'status' => 'failure',
                    'message' => 'Data Not found'
                ];
            }else{
                $response = [
                    'status' => 'success',
                    'data' => $vehicleData
                ];
            }

            return response()->json($response);
        }catch (\Throwable | Exception $exception){
            Log::error($exception->getMessage());
            $response = [
                'status' => 'failure',
                'message' => 'An error occurred with your Query'
            ];


            return response()->json($response);
        }

    }

    function  getStaffDetails($staffNo){
        try {
            $vehicleData = driverDetails::find($staffNo);

            if (!$vehicleData){
                $response = [
                    'status' => 'failure',
                    'message' => 'Data Not found'
                ];
            }else{
                $response = [
                    'status' => 'success',
                    'data' => $vehicleData
                ];
            }



            return response()->json($response);

        }catch (\Throwable | Exception $exception){
            Log::error($exception->getMessage());
            $response = [
                'status' => 'failure',
                'message' => 'An error occurred with your Query'
            ];


            return response()->json($response);
        }

    }


    public function addAccidentRecord(Request $request){

        try {

            $vehicleRecord = $request-> validate([
                "accidentNature"=>'required',
                "accidentType"=>'required',
                "peopleInvolved"=>'required',
                "date"=>'required',
                "time"=>'required',
                "description"=>'required',
                "policeNotified"=>'required',
                "staffNumber"=>'required',
                "driverName"=>'required',
                "driverEmail"=>'required',
                "phoneNo"=>'required',
                "age"=>'required',
                "driverPosition"=>'required',
                "registrationNo"=>'required',
                "modelNo"=>'required',
                "vehicleMake"=>'required',
                "chassisNo"=>'required'
            ]);




            accidentRecord::create($vehicleRecord);

            $response = [
                'status' => 'success',
                'message' => 'Done'
            ];

            return response()->json($response);

        }catch(\Throwable | Exception $exception){
            Log::error($exception->getMessage());

            $response = [
                'status' => 'failure',
                'message' => 'There was an error in your submission'
            ];

            return response()->json($response);
        }







    }
    //*/
}
