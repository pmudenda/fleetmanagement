<?php

namespace App\Services\VehicleManagement;

use App\Enums\DocumentState;
use App\Interfaces\VehicleManagement\RoadTaxService;
use App\Models\VehicleManagement\RoadTax;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RoadTaxServiceImpl implements RoadTaxService
{
    public function getRoadLicence(mixed $registrationNumber): array
    {
        Log::info("Checking Insurance State for $registrationNumber - " . Carbon::today()->toDateString());
        $roadTax = RoadTax::where('reg_no', '=', $registrationNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if (empty($roadTax)) {
            return [DocumentState::Expired, null];
        }

        Log::debug("Road Tax Record $roadTax->valid_to");

        if (Carbon::now()->isAfter(Carbon::parse($roadTax->valid_to))) {
            return [DocumentState::Expired, $roadTax];
        }

        return [DocumentState::Valid, $roadTax];
    }
}
