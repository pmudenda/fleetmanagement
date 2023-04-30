<?php

use App\Http\Controllers\Requisitions\FuelRequisitionController;
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\VehicleManagement\VehicleController;
use App\Http\Controllers\VehicleManagement\VehicleOnBoardingController;
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


/*Route::post('/logout', function () {
})->name('logout');*/

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
        /*
         Route::post('user/attach/{id}', [UsersController::class, 'attach'])->name('user.attach');
         Route::post('user/detach/{id}', [UsersController::class, 'detach'])->name('user.detach');

         Route::get('/list', )->name('home.users');
         Route::get('/users/all', [UsersController::class, 'get'])->name('all.users');
         Route::get('/users/all', [UsersController::class, 'get'])->name('all.users');

         // User Search
         Route::post('user_search', [UserSearchController::class, 'userSearch'])->name('search.user');*/
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

        /** INSURANCE */
        Route::get('insurance/types', function () {
            return view('insurance.types');
        })->name('insurance.types');

        Route::get('insurance/companies', function () {
            return view('insurance.companyList');
        })->name('insurance.companies');


        /** ACCIDENTS */
        Route::get('accidents/types', function () {

        })->name('accident.types');

        Route::get('accidents/nature', function () {

        })->name('accident.nature');


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

    Route::get('/requisition/motor-vehicle', function () {

    })->name('new.vehicle.requisition');

    Route::get('/requisition/fuel', [FuelRequisitionController::class, 'create'])->name('new.fuel.requisition');

    Route::post('/requisition/fuel/save', [FuelRequisitionController::class, 'store'])->name('save.fuel.requisition');

    Route::get('/requisition/parts', function () {

    })->name('new.parts.requisition');
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

//, 'middleware' => 'auth'
Route::group(['prefix' => 'vehicle-management', 'middleware' => 'auth'], function () {

    Route::get('/register', function () {
        return view('vehicleManagement.register.index');
    })->name('new.vehicle');


    Route::get('/vehicle/list', function () {
        $vehicleList = VehicleHeader::get();
        return view('vehicleManagement.vehicleList')
            ->with(compact('vehicleList'));
    })->name('vehicles.list');


    Route::get('/vehicles', function (Request $request) {
        return view('vehicleManagement.vehicleList');
    })->name('vehicle.edit');

    Route::post('vehicles', [VehicleOnBoardingController::class, 'store'])->name('api.vehicle.new');

    Route::get('vehicle/details', [VehicleController::class, 'getDetails'])->name('api.vehicle');

    Route::post('post-chassis-detail', [VehicleOnBoardingController::class, 'storeChassisDetails'])
        ->name('vehicle.chassis.detail');

    Route::get('/insurancelist', function () {
        return view('VehicleManagement.insurancelist');
    })->name('insurancelist');

    Route::get('/legaldocumentlist', function () {
        return view('VehicleManagement.insurancelist');
    })->name('legaldocumentlist');


});


