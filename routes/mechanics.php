<?php

use App\Http\Controllers\WorkShopManagement\MechanicController;
use App\Models\Reference\LabourRates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'mechanic-management'], function () {
        Route::get('mechanic/create', [MechanicController::class, 'create'])
            ->name('mechanic.create');

        Route::post('mechanic/save', [MechanicController::class, 'store'])
            ->name('mechanic.save');

        Route::post('mechanic/update', [MechanicController::class, 'update'])
            ->name('mechanic.update');

        Route::get('mechanic/show', [MechanicController::class, 'show'])
            ->name('mechanic.show');

        Route::get('mechanic/list', [MechanicController::class, 'list'])
            ->name('mechanic.list');

        Route::post('mechanic/find', [MechanicController::class, 'find'])
            ->name('mechanic.search');

        Route::post('mechanic/sync', [MechanicController::class, 'sync'])
            ->name('mechanic.sync');

        Route::post('labour/rates', function (Request $request) {
            try {

                $rate = LabourRates::where('post_code', '=', $request->get('postCode'))
                    //->where('status', '=', StatusHelper::active())
                    ->get();
                if (empty($rate)) {
                    return response()->json([
                        'state' => 'failure',
                        'payload' => []
                    ]);
                }

                return response()->json([
                    'state' => 'success',
                    'payload' => $rate
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                return response()->json([
                    'state' => 'failure',
                    'payload' => []
                ]);
            }
        })
            ->name('labour.rates');
    });
});



