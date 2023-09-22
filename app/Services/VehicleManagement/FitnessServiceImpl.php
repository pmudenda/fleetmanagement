<?php

namespace App\Services\VehicleManagement;

use App\Enums\DocumentState;
use App\Interfaces\VehicleManagement\FitnessService;
use App\Models\VehicleManagement\Fitness;
use App\Models\VehicleManagement\RoadTax;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FitnessServiceImpl implements FitnessService
{
    public function getFitness(mixed $registrationNumber): array
    {
        Log::info("Checking Insurance State for $registrationNumber - " . Carbon::today()->toDateString());

        $record = Fitness::where('reg_no', '=', $registrationNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if (empty($record)) {
            return [DocumentState::Expired, null];
        }

        Log::debug("Fitness Record $record->period_to");

        if (Carbon::now()->isAfter(Carbon::parse($record->period_to))) {
            return [DocumentState::Expired, $record];
        }

        return [DocumentState::Valid, $record];
    }
}
