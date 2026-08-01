<?php

namespace Database\Factories;

use App\Models\AttendanceSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceSessionFactory extends Factory
{
    protected $model = AttendanceSession::class;

    public function definition(): array
    {
        return [
            'internship_id' => \App\Models\Internship::factory(),
            
            'attendance_date' => today(),

            'check_in_at' => null,

            'break_out_at' => null,

            'break_in_at' => null,

            'check_out_at' => null,

            'worked_minutes' => 0,

            'break_minutes' => 0,

            'late_minutes' => 0,
            
            'arrival_status' => 'on_time',

            'status' => 'present',

            'is_verified' => false,
        ];
    }
}