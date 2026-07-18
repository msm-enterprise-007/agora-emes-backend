<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255', 'unique:configs,key'],
            'value' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }
}