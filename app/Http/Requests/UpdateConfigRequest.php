<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('configs', 'key')->ignore($this->route('config')),
            ],
            'value' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }
}