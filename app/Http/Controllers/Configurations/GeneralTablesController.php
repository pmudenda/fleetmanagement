<?php

namespace App\Http\Controllers\Configurations;

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Exceptions\GeneralTableRecordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRecordRequest;
use App\Http\Requests\GeneralTableEditRequest;
use App\Http\Requests\GeneralTableDeleteRequest;
use App\Models\configurations\general\Status;
use App\Models\configurations\GeneralTableConfigurations;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GeneralTablesController extends Controller
{
    public static function findType($ref): array
    {
        $types = collect([
            [
                'ref' => 'accident-types',
                "id" => ConfigurationTypes::ACCIDENT_TYPES->value,
                'title' => 'Accident Types',
            ],
            [
                'ref' => 'insurance-types',
                "id" => ConfigurationTypes::INSURANCE_TYPE->value,
                'title' => 'Insurance Types',
            ],
            [
                'ref' => 'insurance-company',
                "id" => ConfigurationTypes::INSURANCE_COMPANY->value,
                'title' => 'Insurance Companies',
            ],
            [
                'ref' => 'accident-nature',
                "id" => ConfigurationTypes::ACCIDENT_NATURE->value,
                'title' => 'Accident Nature',
            ],
            [
                'ref' => 'vehicle-status',
                "id" => ConfigurationTypes::VEHICLE_STATUS->value,
                'title' => 'Vehicle Status',
            ],
            [
                'ref' => 'fuel-levels',
                "id" => ConfigurationTypes::FUEL_LEVELS->value,
                'title' => 'Fuel Levels',
            ],
            [
                'ref' => 'general-status',
                "id" => ConfigurationTypes::STATUS_GENERAL->value,
                'title' => 'Status General',
            ],
            [
                'ref' => 'store-movement-type',
                "id" => ConfigurationTypes::STORES_MOVEMENT_TYPES->value,
                'title' => 'Movement Types',
            ],
            [
                'ref' => 'insurance-sub-types',
                "id" => ConfigurationTypes::INSURANCE_SUB_TYPES->value,
                'title' => 'Insurance SubTypes',
            ],
            [
                'ref' => 'driver-license-class',
                'id' => ConfigurationTypes::LICENSE_CLASS->value,
                'title' => 'Driver License Class'
            ],
            [
                'ref' => 'repair-category',
                'id' => ConfigurationTypes::REPAIR_TYPE->value,
                'title' => 'Vehicle Repair Types'
            ]
        ]);

        $match = $types->where('ref', '=', strtolower($ref));

        if (empty($match)) {
            return 'nothing';
        }

        return [
            'title' => $match->pluck('title')->first(),
            'id' => $match->pluck('id')->first(),
        ] ?? ['title' => 'nothing', 'id' => '0'];
    }


    public function show(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $entries = GeneralTableConfigurations::all();
        $type = 'nothing';

        return view('modules.configurations.generalTables.index')
            ->with(compact('entries', Constants::TYPE_KEY));
    }

    public function openFormTypeView(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {

        $type = $request->get('ref');

        $dbType = self::findType($type);

        $statusList = Status::where(Constants::MODULE, Constants::ALL_MODULES)->get();

        if (strtolower($type) == ConfigurationTypes::VEHICLE_STATUS) {
            $entries = Status::where(Constants::MODULE, Constants::VEHICLE_MODULE)->get();
        } elseif (strtolower($type) == ConfigurationTypes::STATUS_GENERAL) {
            $entries = Status::where(Constants::MODULE, '!=', Constants::VEHICLE_MODULE)->get();
        } else {
            $entries = GeneralTableConfigurations::where(Constants::TYPE_KEY, $dbType['id'])->get();
        }

        return view('modules.configurations.generalTables.index')->with(
            [
                'title' => $dbType['title'],
                'entries' => $entries,
                'type' => $dbType['id'],
                'statusList' => $statusList
            ]);
    }

    public function save(CreateRecordRequest $request): JsonResponse|array
    {
        $user = Auth::user();
        try {
            //$savedData = null;

            /*if (Constants::TYPE_KEY == ConfigurationTypes::VEHICLE_STATUS || Constants::TYPE_KEY == ConfigurationTypes::STATUS_GENERAL) {
                $savedData = Status::firstOrCreate(
                    [
                        'code' => $request->get('code'),
                    ],
                    [
                        'active' => 1,
                        'name' => $request->get('name'),
                        'module' => $request->get(Constants::VEHICLE_MODULE),
                        'created_by' => $user->id,
                    ]);
            } */

            $dbRecord = GeneralTableConfigurations::where('code', '=', $request->get('code'))
                ->where('type', '=', $request->get(Constants::TYPE_KEY))->first();

            if (!empty($dbRecord)) {
                throw new GeneralTableRecordException('Record with Code '
                    . $request->get('code') . ' already Exists');
            }

            $savedData = GeneralTableConfigurations::firstOrCreate(
                [
                    'code' => $request->get('code'),
                    'type' => $request->get(Constants::TYPE_KEY)
                ],
                [
                    'active' => 1,
                    'name' => $request->get('name'),
                    'module' => $request->get(Constants::ALL_MODULES),
                    'created_by' => $user->id
                ]);

            return [
                'success' => true,
                'payload' => $savedData,
                'message' => "Record created successfully",
            ];

        } catch (\Throwable|Exception $exception) {
            Log::error($exception);
            $message = ErrorMessages::getMessage('err_0005');
            if ($exception instanceof GeneralTableRecordException) {
                $message = $exception->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ]);
        }

    }

    public function editRecord(GeneralTableEditRequest $request): JsonResponse
    {
        Log::info('Editing Record');
        try {

            $entry = GeneralTableConfigurations::where('code', '=', $request->get('code'))
                ->where('type', '=', $request->get('type'))
                ->first();

            if(empty($entry)){
                return response()->json([
                    'success' => false,
                    'message' => "Record Not Found",
                ]);
            }

            $entry->name = $request->name;
            $entry->code = $request->code;
            $entry->active = 1;
            $entry->save();

            return response()->json([
                'success' => true,
                'message' => "Record Submitted Successfully",
            ]);

        } catch (\Throwable|Exception $exception) {
            Log::error($exception->getMessage());

            $output = [
                'success' => false,
                'message' => "We could not complete processing your request to an error",
            ];

            return response()->json($output);
        }
    }

    public function deleteRecord(GeneralTableDeleteRequest $request): JsonResponse
    {
        try {

            $entry = GeneralTableConfigurations::where('id', '=', $request->id)
                ->first();

            if(empty($entry)){
                return response()->json([
                    'success' => false,
                    'message' => "Record Not Found",
                ]);
            }

            $entry->active = 0;
            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                'success' => true,
                'message' => "Record Removed Successfully",
            ]);

        } catch (\Throwable|Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => "We could not complete processing your request to an error",
            ]);
        }
    }
}
