<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TrimedStringRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^[a-zA-Z0-9\s\.\-\_\&]+$/', $value)) {
            $fail("The {$attribute} field must only contain letters, numbers, spaces, dots, hyphens, and underscores.");
        }
    }
}
