<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $device = $this->route('device');

        return [
            'user_id' => ['required', 'exists:users,id'],
            'device_name' => ['required', 'string', 'max:255'],
            'device_type' => ['required', 'string', 'max:30'],
            'mac_address' => [
                'required',
                'string',
                Rule::unique('devices', 'mac_address')->ignore($device),
            ],
            'ip_address' => ['nullable', 'ip'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'is_authorized' => ['boolean'],
        ];
    }
}