<?php

namespace App\Http\Controllers\AccidentReporting;

use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Models\configurations\GeneralTableConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class VehicleRecording extends Controller
{
    function index(){
        $minDate= Carbon::now()->subtract('year', 10);

        return view("modules.accidentreporting.vehiclerecording")
            ->with(compact(
                'minDate'
            ));
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
