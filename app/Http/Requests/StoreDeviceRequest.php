<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'device_name' => ['required', 'string', 'max:255'],
            'device_type' => ['required', 'string', 'max:30'],
            'mac_address' => ['required', 'string', 'unique:devices,mac_address'],
            'ip_address' => ['nullable', 'ip'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'is_authorized' => ['boolean'],
        ];
    }
}