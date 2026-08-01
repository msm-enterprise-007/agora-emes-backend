<?php

namespace Database\Factories;

use App\Models\InternshipApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipApplicationFactory extends Factory
{
    protected $model = InternshipApplication::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'application_type' => 'internship',
            'education_level' => 'Licence 3',
            'motivation' => $this->faker->sentence(),
            'phone_mac_address' => 'AA:BB:CC:DD:EE:FF',
            'laptop_mac_address' => '11:22:33:44:55:66',
            'status' => 'pending',
        ];
    }
}