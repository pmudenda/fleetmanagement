<?php

namespace App\Services\Security;

use App\Constants\QueryComparisonOperator;
use App\Exceptions\UserSimulationException;
use App\Models\Security\User;
use App\Models\UserManagement\Simulation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserSimulationService
{

    /**
     * @param $staffNumber
     * @return void
     * @throws UserSimulationException
     */
    public function endSimulation($staffNumber): void
    {
        $activeSimulation = Simulation::where(
            'simulated',
            QueryComparisonOperator::EQUALS,
            $staffNumber
        )->whereNull('simulate_end')
            ->first();

        if (empty($activeSimulation)) {
            //throw  new UserSimulationException("User Is not being simulated");
            return;
        }

        DB::beginTransaction();
        $simulatingUser = $activeSimulation->simulator;
        $activeSimulation->simulate_end = Carbon::now();
        $user = User::where('staff_no', '=', $simulatingUser)
            ->first();
        Auth::loginUsingId($user->id);
        $activeSimulation->save();
        DB::commit();
    }
}
