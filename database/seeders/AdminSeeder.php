<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Administrateur')->first();

        User::updateOrCreate(
            ['email' => 'admin@agora-emes.com'],
            [
                'role_id' => $adminRole->id,
                'matricule' => 'ADM-0001',
                'first_name' => 'Super',
                'last_name' => 'Administrateur',
                'phone' => null,
                'password' => Hash::make('Admin@2026'),
                'is_active' => true,
            ]
        );
    }
}