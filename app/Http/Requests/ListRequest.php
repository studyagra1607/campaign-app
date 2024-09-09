<?php

namespace App\Http\Requests;

use App\Rules\CsvRule;
use Illuminate\Support\Facades\Validator;
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
            'file' => [
                'bail',
                'required',
                'file',
                'mimes:csv,xls,xlsx',
                'max:2560',
                new CsvRule([
                    'name' => 'bail|required|max:255|regex:/^[a-zA-Z0-9\s\.\']+$/',
                    'email' => 'bail|required|email|unique:users,email|max:255',
                    // 'dob' => 'bail|required|date|before:today',
                    // 'address' => 'bail|required|string|max:255',
                    // 'phone' => 'bail|required|string|min:10|max:15',
                    // '' => 'bail|required|string',
                ])
            ],
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'The file field must not be greater than 2MB',
        ];
    }

    public function attributes()
    {
        return [
            
        ];
    }
    
}
