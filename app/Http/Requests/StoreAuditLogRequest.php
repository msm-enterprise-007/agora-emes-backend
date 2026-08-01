<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'action' => ['required', 'string', 'max:255'],
            'entity_type' => ['required', 'string', 'max:255'],
            'entity_id' => ['nullable', 'integer'],
            'ip_address' => ['nullable', 'ip'],
            'user_agent' => ['nullable', 'string'],
            'old_values' => ['nullable', 'array'],
            'new_values' => ['nullable', 'array'],
            'performed_at' => ['required', 'date'],
        ];
    }
}