<?php

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Helpers\StatusHelper;
use App\Http\Controllers\API\RTSAIntegrationController;
use App\Models\Settings\GeneralTable;
use App\Models\Reference\GtaVehicle;
use App\Models\WorkShopManagement\WorkShopTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth=>sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

