<?php

namespace App\Rules;

use App\Http\Traits\CsvParser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class CsvRule implements ValidationRule
{
    use CsvParser;
    
    protected $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = collect($this->CsvToArray($value));

        $data->chunk(100, function ($chunk) use ($fail) {
            foreach ($chunk as $row)
            {
                $validator = Validator::make($row, $this->rules);
                
                if ($validator->fails()) {
                    $template = json_encode($row);
                    $template .= "<ul>";
                    foreach ($validator->errors()->toArray() as $key => $errors) {
                        foreach ($errors as $error) {
                            $template .= "<li>$error</li>";
                        }
                    }
                    $template .= "</ul>";
                    $fail($template);
                    return false;
                };
            };
        });
    }
}
