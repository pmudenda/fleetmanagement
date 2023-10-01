<?php

use App\Http\Controllers\Security\PasswordResetController;
use App\Http\Controllers\UserManagement\ProfileDelegationController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\UserManagement\UserSimulationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'is.active', 'change.password']], function () {

    Route::group(['prefix' => 'user-management'], function () {

        Route::get('user/show', [UsersController::class, 'profile'])->name('profile');

        Route::get('users/new', [UsersController::class, 'create'])->name('users.new');

        Route::get('users/list', [UsersController::class, 'index'])->name('users.list');

        Route::post('users/resetPassword', [PasswordResetController::class])
            ->name('user.reset.password');

        Route::resource('/user', UsersController::class)->except(['update']);

        Route::post('users/get-employee-data', [UsersController::class, 'employeeSearch'])->name('user.search');

        Route::post('users/find-user-data', [UsersController::class, 'userSearch'])->name('find.user');

        Route::post('user/role/attach', [UsersController::class, 'attach'])->name('user.attach');

        Route::post('user/role/detach', [UsersController::class, 'detach'])->name('user.detach');

        Route::post('user/data/sync', [UsersController::class, 'sync'])->name('user.sync');

        Route::post('user/details/update', [UsersController::class, 'update'])->name('user.update');

        Route::group([
            'prefix' => 'profile-delegation',
            'as' => 'user.profile.delegation.'
        ], function () {
            Route::get('create', [ProfileDelegationController::class, 'create'])
                ->name('create');

            Route::post('cancel', [ProfileDelegationController::class, 'cancel'])
                ->name('cancel');

            Route::post('save', [ProfileDelegationController::class, 'store'])
                ->name('store');
        });


        Route::group([
            'prefix' => 'simulation',
            'as' => 'user.simulation.'
        ], function () {
            Route::post('start', [UserSimulationController::class, 'start'])->name('start');

            Route::post('end', [UserSimulationController::class, 'end'])->name('end');
        });

    });
});



