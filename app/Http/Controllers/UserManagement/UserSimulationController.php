<?php

namespace App\Http\Controllers\UserManagement;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Security\User;
use App\Models\Simulation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserSimulationController extends Controller
{
    /**
     * @throws UserNotFoundException
     */
    public function start(Request $request): JsonResponse
    {
        $staffNumber = $request->get('userIdentifier');

        $user = User::where('staff_no', '=', $staffNumber)->first();

        if (empty($user)) {
            throw new UserNotFoundException("User To Simulate Could Not Be Found");
        }

        DB::commit();

        $simulationJustification = $request->get('simulationJustification');

        Simulation::create([
            "created_by" => Auth::user()->staff_no,
            "simulator" => Auth::user()->staff_no,
            "simulated" => $user->staff_no,
            "simulate_start" => Carbon::now(),
            "comments" => $simulationJustification,
        ]);
        session(['simulating' => true]);
        Auth::loginUsingId($user->id);

        DB::commit();

        return response()->json([
            'success' => true,
            'payload' => []
        ]);
    }

    public function end(Request $request): JsonResponse
    {
        $request->session()->forget('name');

        return response()->json([
            'success' => true,
            'payload' => []
        ]);
    }
}
