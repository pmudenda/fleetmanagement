<?php

namespace App\Http\Controllers\UserManagement;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserSimulationException;
use App\Http\Controllers\Controller;
use App\Models\Security\User;
use App\Models\Simulation;
use App\Services\Security\UserSimulationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserSimulationController extends Controller
{
    private UserSimulationService $userSimulationService;

    public function __construct(UserSimulationService $userSimulationService)
    {
        $this->userSimulationService = $userSimulationService;
    }

    /**
     * @throws UserNotFoundException
     * @throws UserSimulationException
     */
    public function start(Request $request): JsonResponse
    {
        try {

            $staffNumber = $request->get('userIdentifier');
            $user = User::where('staff_no', '=', $staffNumber)->first();

            if (empty($user)) {
                throw new UserNotFoundException("User To Simulate Could Not Be Found");
            }

            DB::commit();
            $simulationJustification = $request->get('simulationJustification');
            $activeSimulations = Simulation::where('simulated', '=', $staffNumber)
                ->whereNull('simulate_end')
                ->count();

            if ($activeSimulations > 0) {
                throw new UserSimulationException("User Is Already Being Simulated");
            }

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

        } catch (Exception $e) {
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof UserNotFoundException || $e instanceof UserSimulationException) {
                $message = $e->getMessage();
            }
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    /**
     */
    public function end(Request $request): JsonResponse
    {
        try {

            $simulatedUser = Auth::user();
            $staffNumber = $simulatedUser->staff_no;

            $this->userSimulationService->endSimulation($staffNumber);

            $request->session()->forget('simulating');
            return response()->json([
                'success' => true,
                'payload' => []
            ]);
        } catch (Exception $e) {

            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');

            if ($e instanceof UserNotFoundException) {
                $message = $e->getMessage();
            }
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }
}
