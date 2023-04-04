<?php

use App\Enums;
use App\Http\Controllers\API\BusinessUnitsController;
use App\Http\Controllers\API\CostCenterController;
use App\Http\Controllers\API\OrganizationalUnitsController;
use App\Http\Controllers\Configurations\ConfigVehicleBrandsController;
use App\Models\configurations\vehicle\ConfigVehicleBodyType;
use App\Models\configurations\vehicle\ConfigVehicleModel;
use App\Models\general\DIRECTORATES;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth=>sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/en')->as('brands:')->middleware(['api'])->group(static function (): void {

});

Route::group(['prefix' => 'v1/en'], function (): void {
    /** Brands API **/

    Route::prefix('brands')->middleware(['api'])->group(static function (): void {
        Route::get('/', [ConfigVehicleBrandsController::class, 'index'])->name('brands');
        Route::post('/', [ConfigVehicleBrandsController::class, 'store'])->name('brands');
        Route::delete('/', [ConfigVehicleBrandsController::class, 'destroy'])->name('brands');
    });

    /** MODELS **/

    Route::post('/models', function (Request $request) {
        try {
            $data = $request->all();

            $model = ConfigVehicleModel::create([
                'status' => $request->input('status'),
                'model_guid' => Str::uuid(),
                'dateCreated' => Carbon::now(),
                'brand_guid' => $request->input('brand_guid'),
                'brand_name' => trim(strtoupper($request->input('brand_name'))),

                'model_name' => trim(strtoupper($request->input('model_name'))),
                'model_code' => $request->input('model_code')
            ]);

            return response()->json([
                'state' => 'success',
                'message' => '',
                'payload' => $model
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => 'Error Occurred while Processing request',
                'payload' => []
            ]);
        }
    })->name('models');
    Route::get('/models', function (Request $request) {
        try {
            $data = ConfigVehicleModel::select(DB::raw('*'))
                //->groupBy('brand_guid')
                ->get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('models');
    Route::delete('/models', function (Request $request) {
        try {
            $statusList = [Enums\VehicleStatusEnum::Active];
            $data = ConfigVehicleModel::whereIn('status', $statusList)
                ->get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('models');


    /** BODY TYPES **/

    Route::post('/vehicle/body-types', function (Request $request) {
        try {
            $data = $request->all();

            //TODO validate data

            $model = ConfigVehicleBodyType::create([
                'status' => $request->input('status'),
                'guid' => Str::uuid(),
                'dateCreated' => Carbon::now(),
                'body_type_name' => trim(strtoupper($request->input('body_type_name')))
            ]);

            return response()->json([
                'state' => 'success',
                'message' => '',
                'payload' => $model
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => 'Error Occurred while Processing request',
                'payload' => []
            ]);
        }
    })->name('body.types');
    Route::get('/vehicle/body-types', function (Request $request) {
        try {
            $statusList = [Enums\VehicleStatusEnum::Active, Enums\VehicleStatusEnum::active];
            $data = ConfigVehicleBodyType::whereIn('status', $statusList)->get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('body.types');
    Route::delete('/vehicle/body-types', function (Request $request) {
        try {

            $data = ConfigVehicleBodyType::where('guid', $request->input('guid'));
            $data->status = 'inactive'; //TODO Use Status enum
            $data->save();

            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('body.types');

    /** USERS **/
    Route::post('users', function (Request $request) {
        try {
            $data = collect([

                [
                    'guid' => Str::uuid(),
                    'name' => 'Lovemore Daka',
                    'position' => 'Software Developer',
                    'email' => 'lovemoredaka@zesco.co.zm',
                    'staff_number' => '76737',
                    'last_login' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'two_fac_auth_status' => 'Enabled'
                ],

                [
                    'guid' => Str::uuid(),
                    'name' => 'Shadrick Siwakwi',
                    'position' => 'Software Developer',
                    'email' => 'ssiwakwi@zesco.co.zm',
                    'staff_number' => '76735',
                    'last_login' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'two_fac_auth_status' => 'Enabled'
                ],

                [
                    'guid' => Str::uuid(),
                    'name' => 'Atupele Mboya',
                    'position' => 'Technologist Database',
                    'email' => 'atupelemoboya@zesco.co.zm',
                    'staff_number' => '76736',
                    'last_login' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'two_fac_auth_status' => 'Enabled'
                ],

                [
                    'guid' => Str::uuid(),
                    'name' => 'Shubart Nyimbili',
                    'position' => 'Software Developer',
                    'email' => 'nshubart@zesco.co.zm',
                    'staff_number' => '7YYYY',
                    'last_login' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'two_fac_auth_status' => 'Enabled'
                ],

                [
                    'guid' => Str::uuid(),
                    'name' => 'Gilbert Sibajene',
                    'position' => 'Senior Software Developer',
                    'email' => 'gsibajene@zesco.co.zm',
                    'staff_number' => '7XXXX',
                    'last_login' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'two_fac_auth_status' => 'Enabled'
                ],

                [
                    'guid' => Str::uuid(),
                    'name' => 'James Tembo',
                    'position' => 'Technologist',
                    'email' => 'tembojames@zesco.co.zm',
                    'staff_number' => '7CCCCC',
                    'last_login' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'two_fac_auth_status' => 'Enabled'
                ],

            ]);


            $result = $data->where('staff_number', $request->all()['param']);

            return response()->json([
                'state' => 'success',
                'criteria' => $request->all()['param'],
                'payload' => $result->all()
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('api.users.search');

    Route::get('users', function (Request $request) {
        try {
            $data = User::get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('api.users.list');

    Route::get('users/{key}', function (Request $request, string $id, string $key = null) {
        try {
            $data = User::get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('api.user.profile');

    Route::put('users', function (Request $request) {
        try {
            $data = collect([

                (object)[
                    'name' => 'Lovemore Daka',
                    'position' => 'Software Developer',
                    'email' => 'lovemoredaka@zesco.co.zm',
                    'staff_number' => '76737'
                ],

                (object)[
                    'name' => 'Shadrick Siwakwi',
                    'position' => 'Software Developer',
                    'email' => 'ssiwakwi@zesco.co.zm',
                    'staff_number' => '76735'
                ],

                (object)[
                    'name' => 'Atupele Mboya',
                    'position' => 'Technologist Database',
                    'email' => 'atupelemoboya@zesco.co.zm',
                    'staff_number' => '76736'
                ],

            ]);
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    })->name('api.users.new');

    /* BUSINESS UNITS*/
    Route::get('business-units', BusinessUnitsController::class)->name('business.units');

    Route::get('organizational-units', OrganizationalUnitsController::class)->name('organizational.units');

    Route::get('directorates', function (Request $request) {
        try {
            $month = 60 * 60 * 24 * 30;
            // clear the cache using request
            if ($request->has('cache') && !$request->get('cache')) {
                cache()->forget('directorates');
            }

            $data = cache()->remember('directorates', $month, function () {
                return DIRECTORATES::orderBy('name')->get();
            });
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }

    })->name('directorates');

    /* BUSINESS UNITS*/
    Route::get('cost-centers', CostCenterController::class)->name('cost.centers');

    Route::get('business-areas', function () {
        try {

            $data = [];//BusinessAreas::get();


            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }

    })->name('business.areas');

});
