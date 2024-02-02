<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class InTrimmedRule implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private readonly array $array)
    {
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Str::replace(['.'], '', $value);
        $value = remove_arabic_characters($value);
        if (in_array(trim($value), $this->array)) {
            return;
        }
        $fail(__('validation.in', ['attribute' => $attribute]));
    }
}
