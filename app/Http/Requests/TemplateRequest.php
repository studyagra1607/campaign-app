<?php

namespace App\Http\Requests;

use App\Rules\TrimedStringRule;
use App\Rules\UniqueWithLoginUserRule;
use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
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
            'name' => ['bail', 'required', 'string', 'max:255', new TrimedStringRule, new UniqueWithLoginUserRule('templates', 'name', 'template', $this->route('template'))],
            'file' => [
                'bail',
                'required_without:id',
                'file',
                'mimes:html,htm,text/html',
                'max:2560',
            ],
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'The file field must not be greater than 2MB',
            'file.required_without' => 'The file field is required.',
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
            'name' => preg_replace('/\s+/', ' ', trim($this->name)),
        ]);
    }
}
