<?php

use App\Http\Controllers\Configurations\GeneralTablesController;
use App\Http\Controllers\Requisitions\FuelRequisitionController;
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\VehicleManagement\VehicleController;
use App\Http\Controllers\VehicleManagement\VehicleOnBoardingController;
use App\Http\Controllers\Workflow\WorkflowController;
use App\Models\Article;
use App\Models\vehiclemanagement\ChassisDetail;
use App\Models\vehiclemanagement\VehicleHeader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect(route('login'));
});

Route::get('verify/document-number', function (Request $request): JsonResponse {

    $valid = true;
    if ($request->get('method') == 'registration_number') {
        $valid = VehicleHeader::where('registration_number', trim($request->get('key')))->count() == 0;
    } else if ($request->get('method') == 'chassis') {
        $valid = ChassisDetail::where('chassis_number', trim($request->get('key')))->count() == 0;
    }

    return response()->json([
        'state' => 'success',
        'payload' => [
            'validity' => $valid,
            'message' => $valid ? 'Document number is valid' : 'Document number is invalid'
        ],
        'request' => $request->all()
    ]);
})->name('document.number.validation');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', function () {
        return view('dashboard.home');
    })->name('home');

    Route::group(['prefix' => 'user-management'], function () {

        Route::get('user/profile', function (Request $request) {

            $uuid = $request->uuid ?? '';
            $email = $request->email ?? '';

            if (empty($uuid)) {
                $uuid = Auth()->user()->guid;
                $email = Auth()->user()->email;
            }

            return view('UserManagement.user_profile')
                ->with(['key' => $uuid, 'email' => $email]);
        })->name('profile');
        // Route::get('/current/details', [UsersController::class, 'getCurrentUserDetails'])->name('user.current.details');

        Route::get('users/new', [UsersController::class, 'create'])->name('users.new');

        Route::get('users/list', [UsersController::class, 'index'])->name('users.list');

        Route::get('user', function () {
            return view('UserManagement.viewUser');
        })->name('view.user');

        // user.store
        Route::resource('/user', UsersController::class);

        Route::post('/get-employee-data', [UsersController::class, 'search'])->name('user.search');

        Route::post('user/attach/{id}', [UsersController::class, 'attach'])->name('user.attach');
        Route::post('user/detach/{id}', [UsersController::class, 'detach'])->name('user.detach');

        /*Route::get('/list', )->name('home.users');
        Route::get('/users/all', [UsersController::class, 'get'])->name('all.users');
        Route::get('/users/all', [UsersController::class, 'get'])->name('all.users');*/

        // User Search
        /*Route::post('user_search', [UserSearchController::class, 'userSearch'])->name('search.user');*/
    });

    /** ROLES */
    Route::group(['prefix' => 'security'], function () {
        Route::get('roles/list', function () {
            return view('UserManagement.list_roles');
        })->name('roles.list');

        Route::get('roles/show', function () {
            return view('UserManagement.viewUser');
        })->name('roles.view');


        Route::get('permissions/list', function () {
            return view('UserManagement.permission');
        })->name('permissions.list');

        //Roles
        Route::post('/roles/attach/{id}', [RolesController::class, 'attach'])->name('roles.attach');
        Route::post('/roles/detach/{id}', [RolesController::class, 'detach'])->name('roles.detach');

        Route::resource('roles', RolesController::class);
        //Permission
        Route::resource('permissions', PermissionsController::class);
    });

    Route::group(['prefix' => 'system-configuration'], function () {

        /** GENERAL TABLES */
        Route::group(['prefix' => 'general'], function () {
            Route::get('/open-view', [GeneralTablesController::class, "openFormTypeView"])->name('configuration.general.table');
            Route::get('/types', [GeneralTablesController::class, "show"]);
            Route::post('/general_tables', [GeneralTablesController::class, "save"])->name('save.data');
            Route::put('/edit/{id}', [GeneralTablesController::class, "edit"])->name('edit.data');
            Route::delete('/delete/{id}', [GeneralTablesController::class, "delete"])->name('delete.data');
        });

        Route::get('vehicle/make', function () {
            return view('configurations.vehicle.brands');
        })->name('vehicle.make');

        Route::get('vehicle/models', function () {
            return view('configurations.vehicle.models');
        })->name('vehicle.models');


        Route::get('vehicle/body-types', function () {
            return view('configurations.vehicle.types');
        })->name('vehicle.body.types');
    });

    Route::group(['prefix' => 'requisitions'], function () {
        Route::get('/fuel', [FuelRequisitionController::class, 'create'])->name('new.fuel.requisition');
        Route::get('/fuel/approve', [FuelRequisitionController::class, 'show'])->name('show.fuel.requisition');
        Route::post('/fuel/save', [FuelRequisitionController::class, 'store'])->name('save.fuel.requisition');
        Route::get('/fuel/list', [FuelRequisitionController::class, 'index'])->name('list.fuel.requisition');
    });

    Route::get('searchProjects', function (Request $request) {
        $period = date('M-Y');
        //$period = 'Oct-2022';
        $searchCriteria = strtoupper(trim($request->input('search')));
        $activeProjects = $this->tripService->getActiveProjects($period, $searchCriteria);
        return response()->json(array(
            'items' => $activeProjects,
            'total_count' => $activeProjects->count()
        ));
    })->name('search.project');

    Route::group(['prefix' => 'vehicle-management', 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'onboarding'], function () {
            Route::get('/register', [VehicleOnBoardingController::class, 'start'])->name('new.vehicle');

            Route::post('post-vehicle-assignment', [VehicleOnBoardingController::class, 'store'])->name('vehicle.assignment.detail');

            Route::post('post-vehicle-details', [VehicleOnBoardingController::class, 'storeVehicleHeader'])
                ->name('new.vehicle.header');

            Route::post('post-chassis-details', [VehicleOnBoardingController::class, 'storeChassisDetails'])
                ->name('vehicle.chassis.detail');

            Route::post('post-engine-details', [VehicleOnBoardingController::class, 'storeEngineDetails'])
                ->name('vehicle.engine.detail');

            Route::post('post-costing-details', [VehicleOnBoardingController::class, 'storeCostingDetails'])
                ->name('vehicle.cost.detail');

            Route::post('post-body-details', [VehicleOnBoardingController::class, 'storeBodyDetails'])
                ->name('vehicle.body.detail');
        });

        Route::get('vehicle/details/{ref}', [VehicleController::class, 'getAllDetails'])->name('vehicle.details');

        Route::get('vehicle/details', [VehicleController::class, 'getDetails'])->name('api.vehicle');

        Route::get('articles/fuels', function () {
            return response()->json([
                'payload' => Article::where('group_code', '01')->get(['code', 'name'])
            ]);
        })->name('fuel.types');

        Route::get('/vehicle/list', [VehicleController::class, 'list'])->name('vehicles.list');

        Route::get('/vehicles', function (Request $request) {
            return view('vehicleManagement.vehicleList');
        })->name('vehicle.edit');

        Route::get('/cleanup', function (Request $request) {
            return view('vehicleManagement.migration.index');
        })->name('vehicle.data.cleanup');

    });

    Route::post('/workflow/approve', [WorkflowController::class, 'approve'])->name('workflow.approve');
});


