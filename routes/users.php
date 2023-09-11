<?php

use App\Http\Controllers\Security\PasswordResetController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\UserManagement\UserSimulationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'user-management'], function () {

        Route::get('user/profile', [UsersController::class, 'profile'])->name('profile');

        Route::get('users/new', [UsersController::class, 'create'])->name('users.new');

        Route::get('users/list', [UsersController::class, 'index'])->name('users.list');

        Route::post('users/resetPassword', [PasswordResetController::class, 'resetPassword'])
            ->name('user.reset.password');

        Route::resource('/user', UsersController::class);

        Route::post('/get-employee-data', [UsersController::class, 'search'])->name('user.search');

        Route::post('user/attach', [UsersController::class, 'attach'])->name('user.attach');
        Route::post('user/detach', [UsersController::class, 'detach'])->name('user.detach');

        Route::post('user/sync', [UsersController::class, 'sync'])->name('user.sync');

        Route::get('user/profile/delegation', [UsersController::class, 'delegation'])->name('user.profile.delegation');

        Route::post('user/update', [UsersController::class, 'update'])->name('user.update');

        Route::post('user/simulation/start', [UserSimulationController::class, 'start'])->name('start.user.simulation');

        Route::post('user/simulation/end', [UserSimulationController::class, 'end'])->name('end.user.simulation');
    });
});



