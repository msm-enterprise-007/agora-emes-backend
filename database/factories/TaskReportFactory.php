<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskReportFactory extends Factory
{
    protected $model = TaskReport::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'version' => 1,
            'comment' => $this->faker->paragraph(),
            'file_name' => null,
            'file_path' => null,
            'mime_type' => null,
            'file_size' => null,
            'status' => 'submitted',
            'review_comment' => null,
            'reviewed_by' => null,
            'submitted_at' => now(),
            'reviewed_at' => null,
        ];
    }
}