<?php

namespace App\Http\Requests;

use App\Rules\CsvRule;
use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'bail|required|regex:/^[a-zA-Z0-9\s\.\']+$/',
            'file' => [
                'bail',
                'required',
                'file',
                'mimes:csv,xls,xlsx',
                new CsvRule([
                    'name' => 'required|max:255|regex:/^[a-zA-Z0-9\s\.\']+$/',
                    'email' => 'required|email|unique:users,email|max:255',
                    // 'dob' => 'required|date|before:today',
                    // 'address' => 'required|string|max:255',
                    // 'phone' => 'required|string|min:10|max:15',
                    // '' => 'required|string',
                ])
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'The :attributes field must only contain letters and numbers.'
        ];
    }

    public function attributes()
    {
        return [
            
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
        ]);
    }
    
}
