<?php

namespace Database\Factories;

use App\Models\Internship;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'internship_id' => Internship::factory(),
            'assigned_by' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'due_date' => now()->addWeek(),
            'priority' => 'medium',
            'status' => 'pending',
        ];
    }
}