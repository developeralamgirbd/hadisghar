<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
       $password = (new Password)->length(10)->requireNumeric()->requireUppercase()->length(10)->requireSpecialCharacter();
        return ['required', 'string', $password, 'confirmed'];
    }
}
