<?php

namespace App\Rules;

use App\Services\Auth\RoleService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CanManageRoleRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( ! RoleService::canOverride($value)) {
            $fail("Yout don't have permission to assign selected roles");
        }
    }
}
