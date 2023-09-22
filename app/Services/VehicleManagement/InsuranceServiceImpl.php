<?php

namespace App\Services\VehicleManagement;

use App\Enums\DocumentState;
use App\Interfaces\VehicleManagement\InsuranceService;
use App\Models\VehicleManagement\Insurance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InsuranceServiceImpl implements InsuranceService
{
    public function getCheckInsurance(mixed $registrationNumber): array
    {
        Log::info("Checking Insurance State for $registrationNumber - " . Carbon::today()->toDateString());
        $insurance = Insurance::where('reg_no', '=', $registrationNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if (empty($insurance)) {
            return [DocumentState::Expired, null];
        }
        Log::info("Insurance Record $insurance->period_to");


        if (Carbon::now()->isAfter(Carbon::parse($insurance->period_to))) {
            return [DocumentState::Expired, $insurance];
        }

        return [DocumentState::Valid, $insurance];
    }
}
