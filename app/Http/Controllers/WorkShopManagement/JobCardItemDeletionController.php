<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Exceptions\DataNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\MaterialDetail;
use App\Models\WorkShopManagement\WorkShopVehicleDefect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobCardItemDeletionController extends Controller
{
    const RECORD_REMOVED_SUCCESSFULLY = "Record Removed Successfully";

    public function deleteDefectRecord(Request $request): JsonResponse
    {
        try {

            $entry = WorkShopVehicleDefect::where("id", "=", $request->record_id)
                ->first();

            if (empty($entry)) {
                return response()->json([
                    "success" => false,
                    "message" => SystemMessages::RECORD_NOT_FOUND,
                ]);
            }

            DB::beginTransaction();
            $entry->deleted_at = Carbon::now();
            $entry->save();
            DB::commit();

            return response()->json(FleetMasterJsonResponse::response(
                'success',
                true,
                self::RECORD_REMOVED_SUCCESSFULLY
            ));

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    ErrorMessages::getMessage('err_0005')
                )
            );
        }
    }

    public function deleteServiceRecord(Request $request): JsonResponse
    {
        try {
            $entry = MaterialDetail::where("id", "=", $request->get('record_id'))
                ->first();

            if (empty($entry)) {
                throw new DataNotFoundException(SystemMessages::RECORD_NOT_FOUND);
            }

            DB::beginTransaction();
            $entry->deleted_at = Carbon::now();
            $entry->save();
            DB::commit();

            return response()->json(
                FleetMasterJsonResponse::response(
                    null,
                    true,
                    self::RECORD_REMOVED_SUCCESSFULLY)
            );

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json(
                FleetMasterJsonResponse::response(
                    null,
                    false,
                    ErrorMessages::getMessage('err_0005'))
            );
        }
    }

    public function deleteMaterialRecord(Request $request): JsonResponse
    {
        try {
            $entry = MaterialDetail::where("id", "=", $request->record_id)
                ->first();

            if (empty($entry)) {
                throw new DataNotFoundException(SystemMessages::RECORD_NOT_FOUND);
            }

            DB::beginTransaction();
            $entry->deleted_at = Carbon::now();
            $entry->save();
            DB::commit();

            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    self::RECORD_REMOVED_SUCCESSFULLY
                )
            );

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    ErrorMessages::getMessage('err_0005'))
            );
        }
    }

}
