<?php

namespace App\Http\Requests;

use App\Rules\TrimedStringRule;
use App\Rules\UniqueWithLoginUserRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => ['bail', 'required', 'string', 'max:255', new TrimedStringRule, new UniqueWithLoginUserRule('categories', 'name', 'category', $this->route('category'))],
            'status' => 'boolean',
        ];
    }

    public function messages()
    {
        return [

        ];
    }

    public function attributes()
    {
        return [

        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => setBoolean($this->status),
        ]);
    }
}
