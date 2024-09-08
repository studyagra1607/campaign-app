<?php

namespace App\Http\Requests;

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
            // 'dept_name' => 'required|string|unique:m_department,dept_name'.(isset($this->route('department')->id) ? ','.$this->route('department')->id.',id' : ''),
            'name' => 'required',
            'file' => 'max:1',
            'file.*' => 'required|mimes:xlsx,csv',
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }

}
