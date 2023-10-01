<?php

use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use App\Models\Security\Role;
use App\Models\Security\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'is.active']], function () {
    Route::get('user/change/password', function (Request $request) {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        if (empty($request->get('key'))) {
            return redirect(route('users.list'));
        }

        $id = (int)$request->get('key');
        $user = User::where('id', '=', $id)->first();
        $roles = Role::all();
        $passwordChangeOnly = true;
        return view('modules.userManagement.show')
            ->with(compact(
                    'user',
                    'passwordChangeOnly',
                    'roles'
                )
            );

    })->name('password.change');
});

Route::group(['middleware' => ['auth', 'is.active', 'change.password'], 'prefix' => 'security'], function () {

    Route::post('/roles/assign/permission', [RolesController::class, 'assignPermission'])
        ->name('roles.assign.permission');

    Route::post('/roles/revoke/permission', [RolesController::class, 'revokePermission'])
        ->name('roles.revoke.permission');

    Route::resource('roles', RolesController::class);

    Route::post('roles/update/{role}', [RolesController::class, 'updateRole'])
        ->name('roles.update');

    Route::resource('permissions', PermissionsController::class);
});
