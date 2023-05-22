<?php

namespace App\Http\Controllers\Configurations;

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Exceptions\GeneralTableRecordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRecordRequest;
use App\Models\configurations\general\Status;
use App\Models\configurations\GeneralTableConfigurations;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GeneralTablesController extends Controller
{

    public static function findType($ref): array
    {

        $types = collect([
            [
                'ref' => 'accidenttypes',
                "id" => ConfigurationTypes::ACCIDENT_TYPES->value,
                'title' => 'Accident Types',
            ],
            [
                'ref' => 'insurancetypes',
                "id" => ConfigurationTypes::INSURANCE_TYPE->value,
                'title' => 'Insurance Types',
            ],
            [
                'ref' => 'insurancecompany',
                "id" => ConfigurationTypes::INSURANCE_COMPANY->value,
                'title' => 'Insurance Companies',
            ],
            [
                'ref' => 'accidentnature',
                "id" => ConfigurationTypes::ACCIDENT_NATURE->value,
                'title' => 'Accident Nature',
            ],
            [
                'ref' => 'vehiclestatus',
                "id" => ConfigurationTypes::VEHICLE_STATUS->value,
                'title' => 'Vehicle Status',
            ],
            [
                'ref' => 'fuellevels',
                "id" => ConfigurationTypes::FUEL_LEVELS->value,
                'title' => 'Fuel Levels',
            ],
            [
                'ref' => 'generalstatus',
                "id" => ConfigurationTypes::STATUS_GENERAL->value,
                'title' => 'Status General',
            ],
            [
                'ref' => 'storemovementtype',
                "id" => ConfigurationTypes::STORES_MOVEMENT_TYPES->value,
                'title' => 'Movement Types',
            ],
            [
                'ref' => 'insurancesubtypes',
                "id" => ConfigurationTypes::INSURANCE_SUB_TYPES->value,
                'title' => 'Insurance SubTypes',
            ],
            [
                'ref' => 'driverLicenseClass',
                'id' => ConfigurationTypes::LICENSE_CLASS->value,
                'title' => 'Driver License Class'
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
            $message = ErrorMessages::internalServerError;
            if ($exception instanceof GeneralTableRecordException) {
                $message = $exception->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ]);
        }

    }

    public function edit(Request $request, $id): JsonResponse
    {
        try {
            $formData = $request->validate([
                'name' => 'required',
                'code' => 'required',
                'status' => 'required',
            ]);

            $entry = GeneralTableConfigurations::find($id);
            $entry->name = $formData['name'];
            $entry->code = $formData['code'];
            $entry->status = 1;//$formData['status'];
            $entry->save();
            return response()->json([]);

        } catch (\Throwable|Exception $exception) {
            Log::error($exception->getMessage());

            $output = [
                'status' => 'failure',
                'message' => "Your submission had an error",
            ];

            return response()->json($output);
        }
    }

    public function delete(GeneralTableConfigurations $id)
    {
        $id->delete();
    }
}
