<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\PotentiallyTranslatedString;

class EmailorPhoneRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            Validator::validate(
                ['username' => $value],
                ['username' => 'regex:/09[0-9]{9}/'],
                ['username' => 'username format is invalid']
            );
        }
    }
}
