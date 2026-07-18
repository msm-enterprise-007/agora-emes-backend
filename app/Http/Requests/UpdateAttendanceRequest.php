<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'internship_id' => ['required', 'exists:internships,id'],
            'attendance_date' => ['required', 'date'],
            'check_in_at' => ['nullable', 'date'],
            'break_out_at' => ['nullable', 'date'],
            'break_in_at' => ['nullable', 'date'],
            'check_out_at' => ['nullable', 'date'],
            'worked_minutes' => ['nullable', 'integer', 'min:0'],
            'break_minutes' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string'],
            'is_verified' => ['boolean'],
        ];
    }
}