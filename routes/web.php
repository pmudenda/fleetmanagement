<?php

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

    $valid =  true;
    if($request->get('method') == 'registration_number'){
        $valid = VehicleHeader::where('registration_number', trim($request->get('key')))->count() == 0;
    }else if($request->get('method') == 'chassis'){
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

    Route::group(['prefix' => 'employeeManagement'], function () {
    });

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

        Route::get('users/new', function () {
            return view('UserManagement.addUser');
        })->name('users.new');

        Route::get('users/list', function () {
            return view('UserManagement.list');
        })->name('users.list');


        Route::get('user', function () {
            return view('UserManagement.viewUser');
        })->name('view.user');


        /** ROLES */

        Route::get('roles/list', function () {
            return view('UserManagement.list_roles');
        })->name('roles.list');

        Route::get('roles/show', function () {
            return view('UserManagement.viewUser');
        })->name('roles.view');


        Route::get('permissions/list', function () {
            return view('UserManagement.permission');
        })->name('permissions.list');


    });


    Route::group(['prefix' => 'system-configuration'], function () {

        /** INSURANCE */
        Route::get('insurance/types', function () {
            return view('UserManagement.profile');
        })->name('insurance.types');

        Route::get('insurance/companies', function () {
            return view('UserManagement.list');
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
});


