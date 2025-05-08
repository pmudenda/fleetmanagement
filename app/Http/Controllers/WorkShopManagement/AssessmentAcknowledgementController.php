<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Exceptions\InvalidAssessmentSignatoryException;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Reference\PHCMSEmployee;
use App\Models\WorkShopManagement\JobCardHeader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AssessmentAcknowledgementController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $staffNumber = $request->get('loginId');

            $driver = PHCMSEmployee::where('con_st_code', '=', 'ACT')
                ->where(function ($query) use ($staffNumber) {
                    $query->where('con_per_no', '=', $staffNumber);
                })
                ->first();

            $driverStaffNo = $driver->con_per_no ?? $driver->alt_per_no;

            if (empty($driver)) {
                return response()->json([
                    "success" => false,
                    "message" => "Assessment Signatory is not a driver",
                ]);
            }

            $entry = JobCardHeader::where("job_card_no", "=", $request->get('reference'))
                ->first();

            if (empty($entry)) {
                throw new
                InvalidAssessmentSignatoryException(SystemMessages::RECORD_NOT_FOUND);
            }

            if ((($driverStaffNo != $staffNumber)
                    || ($entry->driver_in != $staffNumber))
                || ($driverStaffNo !== $entry->driver_in)) {
                throw new
                InvalidAssessmentSignatoryException(
                    "Assessment Signatory is not the driver who brought the vehicle"
                );
            }

            if ($driver instanceof Driver
                && Hash::check($request->get('password'),
                    $driver->password)) {
                Log::info('Commence Actual eSignature Authentication');
            }

            if ($driver) {
                Log::info('eSignature Successful');
                $entry->updated_at = Carbon::now();
                $entry->driver_acknowledged = 'Y';
                $entry->date_acknowledged = Carbon::now();
                $entry->save();
            } else {
                Log::info('eSignature Failed');
                throw new
                InvalidAssessmentSignatoryException("Invalid Credentials");
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $message = ErrorMessages::getMessage('err_0005');

            if ($exception instanceof InvalidAssessmentSignatoryException) {
                $message = $exception->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        return response()->json([
            'payload' => [],
            "success" => true,
            "message" => "Assessment Signed Successfully",
        ]);
    }

}
