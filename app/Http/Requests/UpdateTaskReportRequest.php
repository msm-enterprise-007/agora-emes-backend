<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id' => ['required', 'exists:tasks,id'],
            'version' => ['required', 'integer', 'min:1'],
            'comment' => ['nullable', 'string'],
            'file_name' => ['nullable', 'string', 'max:255'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'mime_type' => ['nullable', 'string', 'max:100'],
            'file_size' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string'],
            'review_comment' => ['nullable', 'string'],
            'reviewed_by' => ['nullable', 'exists:users,id'],
            'submitted_at' => ['required', 'date'],
            'reviewed_at' => ['nullable', 'date'],
        ];
    }
}