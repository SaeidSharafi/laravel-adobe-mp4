<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NationalcodeRule implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( ! preg_match('/^\d{8}$|^\d{10}$|^\d{12}$|^\d{14}$/', $value)) {
            $fail('validation.national_code.wrong')->translate();
            return;
        }
        for ($i = 0; $i < 10; $i++) {
            if (preg_match('/^' . $i . '{8}$|^' . $i . '{10}$|^' . $i . '{12}$|^' . $i . '{14}$/', $value)) {
                $fail('validation.national_code.wrong')->translate();
                return;
            }
        }

        if (config('app.ignore_national_code_validation')) {
            return;
        }
        if (mb_strlen($value) > 10) {
            return;
        }
        for ($i = 0, $sum = 0; $i < 9; $i++) {
            $sum += ((10 - $i) * (int) (mb_substr($value, $i, 1)));
        }

        $ret    = $sum % 11;
        $parity = (int) (mb_substr($value, 9, 1));
        if (($ret < 2 && $ret === $parity) || ($ret >= 2 && $ret === 11 - $parity)) {
            return;
        }

        $fail('validation.national_code.wrong')->translate();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.national_code.wrong');
    }
}
