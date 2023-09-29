<?php

namespace App\Services\WorkShopManagement;

use App\Http\Requests\WorkShopManagement\JobCardRequest;
use Illuminate\Support\Facades\Log;

class JobCardValidationService
{
    public function validate(JobCardRequest $request): void
    {
       // implement validation functionality
        Log::info("Validate Job Card ".$request->get('vehicle_registration'));
    }
}
