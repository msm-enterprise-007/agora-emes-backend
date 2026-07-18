<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Internship;
use App\Models\InternshipApplication;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InternshipApplicationService
{
    public function approve(InternshipApplication $application, int $reviewerId): InternshipApplication
    {
        return DB::transaction(function () use ($application, $reviewerId) {

            $internRole = Role::where('slug', 'intern')->firstOrFail();

            $temporaryPassword = Str::password(12);

            $user = User::create([
                'role_id' => $internRole->id,
                'matricule' => $this->generateMatricule(),
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'email' => $application->email,
                'password' => Hash::make($temporaryPassword),
                'is_active' => true,
            ]);

            Internship::create([
                'user_id' => $user->id,
                'title' => 'Stage',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'work_start_time' => '08:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:00:00',
                'work_end_time' => '17:00:00',
                'authorized_absence_minutes' => 120,
                'status' => 'active',
            ]);

            Device::create([
                'user_id' => $user->id,
                'device_name' => 'Téléphone',
                'device_type' => 'phone',
                'mac_address' => $application->phone_mac_address,
                'is_authorized' => true,
            ]);

            if ($application->laptop_mac_address) {
                Device::create([
                    'user_id' => $user->id,
                    'device_name' => 'Ordinateur',
                    'device_type' => 'computer',
                    'mac_address' => $application->laptop_mac_address,
                    'is_authorized' => true,
                ]);
            }

            $application->update([
                'status' => 'approved',
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
            ]);

            return $application;
        });
    }

    private function generateMatricule(): string
    {
        return 'STG-' . now()->format('Y') . '-' . str_pad(
            User::count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}