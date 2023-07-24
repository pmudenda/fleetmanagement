<?php

use App\Http\Controllers\Security\PasswordResetController;
use App\Http\Controllers\UserManagement\UsersController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user-management'], function () {

    Route::get('user/profile', [UsersController::class, 'profile'])->name('profile');

    Route::get('users/new', [UsersController::class, 'create'])->name('users.new');

    Route::get('users/list', [UsersController::class, 'index'])->name('users.list');

    Route::post('users/resetPassword', [PasswordResetController::class, 'resetPassword'])->name('user.reset.password');

    // user.store
    Route::resource('/user', UsersController::class);

    Route::post('/get-employee-data', [UsersController::class, 'search'])->name('user.search');
    Route::post('user/attach', [UsersController::class, 'attach'])->name('user.attach');
    Route::post('user/detach', [UsersController::class, 'detach'])->name('user.detach');

    Route::get('sync/{id}', [UsersController::class, 'sync'])->name('user.sync');

    Route::post('update/{id}', [UsersController::class, 'update'])->name('user.update');
});



