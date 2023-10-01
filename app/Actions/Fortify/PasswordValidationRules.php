<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\Rule;
use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate password.
     *
     * @return array<int, Rule|array|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string', new Password, 'confirmed',
            \Illuminate\Validation\Rules\Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
        ];
    }
}
