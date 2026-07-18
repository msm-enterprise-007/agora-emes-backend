<?php

namespace Database\Factories;

use App\Models\Internship;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipFactory extends Factory
{
    protected $model = Internship::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),

            'title' => fake()->jobTitle(),

            'start_date' => now()->subDay(),

            'end_date' => now()->addMonth(),

            'work_start_time' => '08:00:00',

            'break_start_time' => '12:00:00',

            'break_end_time' => '13:00:00',

            'work_end_time' => '17:00:00',

            'authorized_absence_minutes' => 120,

            'status' => 'active',

            'description' => fake()->sentence(),
        ];
    }
}