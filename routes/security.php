<?php

use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;


Route::group(['middleware' => ['auth', 'is.active', 'change.password'], 'prefix' => 'security'], function () {

    Route::post('/roles/assign/permission', [RolesController::class, 'assignPermission'])
        ->name('roles.assign.permission');

    Route::post('/roles/revoke/permission', [RolesController::class, 'revokePermission'])
        ->name('roles.revoke.permission');

    Route::resource('roles', RolesController::class);

    Route::post('roles/update/{role}', [RolesController::class, 'updateRole'])
        ->name('roles.update');

    Route::resource('permissions', PermissionsController::class);

    Route::get('user/change/password', function (Request $request) {
        return redirect(URL::signedRoute('profile', ['key' => $request->get('key')]));
    })->name('password.change');

});
