<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),

            'device_name' => fake()->randomElement([
                'Laptop',
                'Téléphone',
            ]),

            'device_type' => fake()->randomElement([
                'laptop',
                'phone',
            ]),

            'mac_address' => fake()->unique()->macAddress(),

            'ip_address' => fake()->ipv4(),

            'manufacturer' => fake()->company(),

            'is_authorized' => true,

            'last_seen_at' => now(),
        ];
    }
}