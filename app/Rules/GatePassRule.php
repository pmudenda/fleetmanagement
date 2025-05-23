<?php

namespace App\Rules;

use App\Enums\GatePassStatus;
use App\Helpers\StatusHelper;
use App\Models\GatePass\GatePass;
use App\Models\VehicleManagement\VehicleHeader;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GatePassRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $vehicle = VehicleHeader::where('registration_number', $value)->first();

        if(!$vehicle){
             $fail('This vehicle does not exist.');
             return;
        }

        if($vehicle->status != StatusHelper::active()){
            $fail("This vehicle is currently {$vehicle->state->name}");
            return;

        }

        if (in_array($vehicle->body_type_code, ['11', '23', '24', '25', '30', '32', '37'])){
            $fail("Gate Pass not allowed for this Asset");
            return;

        }

        $exists = GatePass::where('reg_no', $vehicle->registration_number)
            ->where('status', '!=', GatePassStatus::REJECTED)
            ->where('expires_at', '>', now())
            ->exists();

        if($exists){
            $fail('This vehicle already has an active gate pass.');
            return;

        }

    }
}
