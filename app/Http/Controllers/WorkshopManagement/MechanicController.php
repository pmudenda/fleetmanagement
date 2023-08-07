<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Reference\PHCMSEmployee;
use App\Models\WorkShopManagement\Mechanic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function find(Request $request): JsonResponse
    {
        try {
            Log::info('Searching for mechanic ' . $request->get('staff_no'));

            $mechanic = Mechanic::where('staff_no', '=', $request->get('staff_no'))
                ->where('status', '=', StatusHelper::active())
                ->first();

            if (empty($mechanic)) {
                return response()->json([
                    'state' => 'failure',
                    'payload' => []
                ]);
            }

            $job_code = PHCMSEmployee::where('con_per_no', '=', $mechanic->staff_no)->first()->pluck('job_code');

            return response()->json([
                'state' => 'success',
                'payload' => [
                    'job_code' => $job_code,
                    'mechanic' => $mechanic
                ]
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
