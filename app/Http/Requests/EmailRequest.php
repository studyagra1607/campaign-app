<?php

namespace App\Http\Requests;

use App\Rules\CsvRule;
use App\Rules\ExistsWithLoginUserRule;
use App\Rules\TrimedStringRule;
use App\Rules\UniqueWithLoginUserRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
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
            'name' => ['bail', 'required', 'string', 'max:255', new TrimedStringRule()],
            'email' => ['bail', 'required', 'email', 'max:255', new UniqueWithLoginUserRule('emails', 'email', 'email', $this->route('email'))],
            'subscribe' => 'boolean',
            'status' => 'boolean',
            'category_ids' => ['bail', 'nullable', 'array', new ExistsWithLoginUserRule('categories')],
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
            'category_ids' => 'category'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'subscribe' => setBoolean($this->subscribe),
            'status' => setBoolean($this->status),
        ]);
    }
    
}
