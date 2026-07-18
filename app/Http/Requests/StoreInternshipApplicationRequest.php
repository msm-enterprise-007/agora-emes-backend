<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInternshipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'application_type' => [
                'required',
                Rule::in(['internship', 'formation']),
            ],
            'education_level' => ['required', 'string', 'max:150'],
            'motivation' => ['nullable', 'string'],
            'phone_mac_address' => ['required', 'string', 'max:17'],
            'laptop_mac_address' => ['nullable', 'string', 'max:17'],
        ];
    }
}