<?php

namespace App\Http\Requests;

use App\Rules\ExistsWithLoginUserRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UploadEmailCsvRequest extends FormRequest
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
            'category_ids' => [
                'bail',
                'required',
                'array',
                new ExistsWithLoginUserRule('categories'),
            ],
            'file' => [
                'bail',
                'required',
                'file',
                'mimes:csv,xls,xlsx',
                'max:10240',
            ],
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'The file field must not be greater than 10MB',
        ];
    }

    public function attributes()
    {
        return [
            
        ];
    }
    
}
