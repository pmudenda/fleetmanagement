<?php

namespace App\Services\VehicleManagement;

use App\Constants\QueryComparisonOperator;
use App\Enums\DocumentState;
use App\Exceptions\DataNotFoundException;
use App\Helpers\StatusHelper;
use App\Models\VehicleManagement\Insurance;
use App\Models\VehicleManagement\TomCardAllocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsuranceService
{
    public function getCheckInsurance(mixed $registrationNumber): array
    {
        Log::info("Checking Insurance State for $registrationNumber - "
            . Carbon::today()->toDateString());
        $insurance = Insurance::where('reg_no', '=', $registrationNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if ("systeminfo.enableInsurance") {
            return [DocumentState::Valid, $insurance];
        }

        if (empty($insurance)) {
            return [DocumentState::Expired, null];
        }
        Log::info("Insurance Record $insurance->period_to");


        if (Carbon::now()->isAfter(Carbon::parse($insurance->period_to))) {
            return [DocumentState::Expired, $insurance];
        }

        return [DocumentState::Valid, $insurance];
    }

    public function save(Request $request): void
    {

    }
}
