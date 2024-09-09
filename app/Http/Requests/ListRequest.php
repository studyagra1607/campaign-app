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
            // 'name' => 'bail|required|regex:/^[a-zA-Z0-9\s\.\']+$/',
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
            // 'name.regex' => 'The :attributes field must only contain letters and numbers.',
            'file.max' => 'The file field must not be greater than 2MB',
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
    
    // public function withValidator($validator)
    // {
    //     if ($validator->fails()) {
    //         return;
    //     }

    //     $validator->after(function ($validator) {
    //         if (!$this->file('file')) {
    //             $validator->errors()->add('file', 'The file is required.');
    //         } else {
    //             $this->validateFile($validator);
    //         }
    //     });
    // }

    // protected function validateFile($validator)
    // {
    //     $rules = [
    //         'file' => [
    //             'bail',
    //             'required',
    //             'file',
    //             'mimes:csv,xls,xlsx',
    //             'max:2560',
    //             new CsvRule([
    //                 'name' => 'bail|required|max:255|regex:/^[a-zA-Z0-9\s\.\']+$/',
    //                 'email' => 'bail|required|email|unique:users,email|max:255',
    //                 // 'dob' => 'bail|required|date|before:today',
    //                 // 'address' => 'bail|required|string|max:255',
    //                 // 'phone' => 'bail|required|string|min:10|max:15',
    //                 // '' => 'bail|required|string',
    //             ])
    //         ],
    //     ];

    //     $messages = [
    //         'file.max' => 'The uploaded file may not be larger than 2MB.',
    //     ];
        
    //     $fileValidator = Validator::make($this->all(), $rules, $messages);

    //     if ($fileValidator->fails()) {
    //         $validator->errors()->add('file', $fileValidator->errors()->first('file'));
    //     }
    // }
    
}
