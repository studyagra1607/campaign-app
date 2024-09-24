<?php

namespace App\Http\Requests;

use App\Rules\ExistsWithLoginUserRule;
use App\Rules\TrimedStringRule;
use App\Rules\UniqueWithLoginUserRule;
use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
            'name' => ['bail', 'required', 'string', 'max:255', new TrimedStringRule, new UniqueWithLoginUserRule('campaigns', 'name', 'campaign', $this->route('campaign'))],
            'status' => 'boolean',
            'category_id' => ['bail', 'required', new ExistsWithLoginUserRule('categories')],
            'template_id' => ['bail', 'required', new ExistsWithLoginUserRule('templates')],
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
            'category_id' => 'category',
            'template_id' => 'template',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => setBoolean($this->status),
        ]);
    }
}
