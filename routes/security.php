<?php

use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\RolesController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth', 'prefix' => 'security'], function () {

    Route::post('/roles/assign/permission', [RolesController::class, 'assignPermission'])
        ->name('roles.assign.permission');

    Route::post('/roles/revoke/permission', [RolesController::class, 'revokePermission'])
        ->name('roles.revoke.permission');

    Route::resource('roles', RolesController::class);

    Route::post('roles/update/{role}', [RolesController::class, 'updateRole'])
        ->name('roles.update');

    Route::resource('permissions', PermissionsController::class);

});
