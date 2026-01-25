<?php

use App\Http\Controllers\Documents\DocumentController;
use App\Http\Controllers\FuelManagement\FuelRequisitionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SessionStateController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\WorkShopManagement\PdfJobController;
use App\Services\VehicleManagement\VehicleDetailsService;
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
    return redirect(route('login'));
});

Route::post('logout', [HomeController::class, 'logout'])->name('logout');

Route::get('gate/pass', [HomeController::class, 'gatePass'])->name('gate.pass');

Route::get('print/job/card', [PdfJobController::class, "index"])->name('print.job.card');

Route::group(['middleware' => ['auth','is.active','change.password']], function () {

     Route::get('/error', function (Request $request) {
         return view('error')->with(['error' => $request->get('message')]);
     })->name('error');

    Route::post('getStatus', SessionStateController::class)->name('session.status');

    Route::get('/home', [HomeController::class, 'dashboard'])->name('home');

    Route::get('document/followup', [DocumentController::class, 'documentFollowup'])
        ->name('document.followup');

    Route::post('document/audit/trail', [DocumentController::class, 'documentAuditTrail'])
        ->name('document.audit.trail');
});

require __DIR__ . '/gps.php';





