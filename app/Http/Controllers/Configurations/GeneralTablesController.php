<?php

namespace App\Http\Controllers\Configurations;

use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
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


    public static function findType($id): string
    {
        $types = collect([
            [
                "id" => ConfigurationTypes::ACCIDENT_TYPES->value,
                'title' => 'Accident Types',
            ],
            [
                "id" => ConfigurationTypes::INSURANCE_TYPE->value,
                'title' => 'Insurance Types',
            ],
            [
                "id" => ConfigurationTypes::INSURANCE_COMPANY->value,
                'title' => 'Insurance Companies',
            ],
            [
                "id" => ConfigurationTypes::ACCIDENT_NATURE->value,
                'title' => 'Accident Nature',
            ],
            [
                "id" => ConfigurationTypes::BUSINESS_AREAS->value,
                'title' => 'Business Areas',
            ],
            [
                "id" => ConfigurationTypes::VEHICLE_STATUS->value,
                'title' => 'Vehicle Status',
            ],
            [
                "id" => ConfigurationTypes::FUEL_LEVELS->value,
                'title' => 'Fuel Levels',
            ],
            [
                "id" => ConfigurationTypes::STATUS_GENERAL->value,
                'title' => 'Status General',
            ],
            [
                "id" => ConfigurationTypes::STORES_MOVEMENT_TYPES->value,
                'title' => 'Movement Types',
            ],
            [
                "id" => ConfigurationTypes::INSURANCE_SUB_TYPES->value,
                'title' => 'Insurance SubTypes',
            ],
        ]);

        $match = $types->where('id', '=', strtolower($id));

        if (empty($match)) {
            return 'nothing';
        }

        return $match->pluck('title')->first() ?? 'nothing';
    }


    public function show(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $entries = GeneralTableConfigurations::all();
        $type = 'nothing';

        return view('configurations.generalTables.index')
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
            $entries = GeneralTableConfigurations::where(Constants::TYPE_KEY, $type)->get();
        }

        return view('configurations.generalTables.index')->with(
            [
                'type' => $dbType,
                'entries' => $entries,
                'typeStr' => $type,
                'statusList' => $statusList
            ]);
    }

    public function save(CreateRecordRequest $request): JsonResponse|array
    {
        $user = Auth::user();
        try {
            //$savedData = null;

            if (Constants::TYPE_KEY == ConfigurationTypes::VEHICLE_STATUS || Constants::TYPE_KEY == ConfigurationTypes::STATUS_GENERAL) {

                $savedData = Status::firstOrCreate(
                    [
                        'code' => $request->get('code'),
                    ],
                    [
                        'active' => $request->get('status') ?? 1,
                        'name' => $request->get('name'),
                        'module' => $request->get(Constants::VEHICLE_MODULE),
                        'created_by' => $user->id,
                    ]);
            } else {
                $savedData = GeneralTableConfigurations::firstOrCreate(
                    [
                        'code' => $request->get('code'),
                        'type' => $request->get(Constants::TYPE_KEY)
                    ],
                    [
                        'active' => $request->get('status'),
                        'name' => $request->get('name'),
                        'module' => $request->get(Constants::ALL_MODULES),
                        'created_by' => $user->id
                    ]);
            }

            return [
                'success' => true,
                'payload' => $savedData,
                'message' => "Record created successfully",
            ];

        } catch (\Throwable|Exception $exception) {
            Log::error($exception);
            return response()->json([
                'success' => false,
                'message' => "Your submission had an error",
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
            $entry->status = $formData['status'];
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
