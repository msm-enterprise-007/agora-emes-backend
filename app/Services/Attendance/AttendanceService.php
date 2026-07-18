<?php

namespace App\Services\Attendance;

use App\Models\AttendanceSession;
use App\Models\Device;
use App\Models\Internship;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public function checkIn(
        Internship $internship,
        string $macAddress
    ): AttendanceSession {

        $this->ensureAuthorizedDevice(
            $internship,
            $macAddress
        );

        if (
            $internship->status !== 'active'
        ) {
            throw ValidationException::withMessages([
                'internship' => ['Ce stage n\'est pas actif.'],
            ]);
        }
        
        if (
            now()->toDateString() < $internship->start_date->toDateString()
        ) {
            throw ValidationException::withMessages([
                'internship' => ['Le stage n\'a pas encore commencé.'],
            ]);
        }
        
        if (
            now()->toDateString() > $internship->end_date->toDateString()
        ) {
            throw ValidationException::withMessages([
                'internship' => ['Le stage est terminé.'],
            ]);
        }

        $today = Carbon::today();

        $existingSession = AttendanceSession::where(
                'internship_id',
                $internship->id
            )
            ->whereDate('attendance_date', $today)
            ->whereNull('check_out_at')
            ->first();
        
        if ($existingSession && $existingSession->check_in_at) {
            throw ValidationException::withMessages([
                'attendance' => [
                    'Une session de pointage est déjà en cours.'
                ],
            ]);
        }

        $session = AttendanceSession::firstOrCreate(
            [
                'internship_id' => $internship->id,
                'attendance_date' => $today,
            ],
            [
                'status' => 'present',
            ]
        );

        if ($session->check_in_at) {
            throw ValidationException::withMessages([
                'attendance' => ['Pointage déjà effectué.'],
            ]);
        }

        $checkIn = now();
        $workStart = Carbon::parse(
            $today->toDateString().' '.$internship->work_start_time
        );
        
        if ($checkIn->greaterThan($workStart)) {
            $lateMinutes = $workStart->diffInMinutes($checkIn);
        } else {
            $lateMinutes = 0;
        }
        
        $session->update([
            'check_in_at' => $checkIn,
            'late_minutes' => $lateMinutes,
            'arrival_status' => $lateMinutes > 0
                ? 'late'
                : 'on_time',
        ]);

        return $session->fresh();
    }

    public function breakOut(
        AttendanceSession $session
    ): AttendanceSession {
    
        if (! $session->check_in_at) {
            throw ValidationException::withMessages([
                'attendance' => ['Le pointage d\'entrée est requis.'],
            ]);
        }
    
        if ($session->break_out_at) {
            throw ValidationException::withMessages([
                'attendance' => ['La pause a déjà commencé.'],
            ]);
        }
    
        $session->update([
            'break_out_at' => now(),
        ]);
    
        return $session->fresh();
    }

    public function breakIn(
        AttendanceSession $session
    ): AttendanceSession {
    
        if (! $session->break_out_at) {
            throw ValidationException::withMessages([
                'attendance' => ['La pause n\'a pas commencé.'],
            ]);
        }
    
        if ($session->break_in_at) {
            throw ValidationException::withMessages([
                'attendance' => ['Le retour de pause a déjà été effectué.'],
            ]);
        }
    
        $session->update([
            'break_in_at' => now(),
        ]);
    
        return $session->fresh();
    }

    public function checkOut(
        AttendanceSession $session
    ): AttendanceSession {
    
        if (! $session->check_in_at) {
            throw ValidationException::withMessages([
                'attendance' => ['Le pointage d\'entrée est requis.'],
            ]);
        }
    
        if ($session->check_out_at) {
            throw ValidationException::withMessages([
                'attendance' => ['Le pointage de sortie a déjà été effectué.'],
            ]);
        }
    
        $workedMinutes = Carbon::parse($session->check_in_at)
            ->diffInMinutes(now());
    
        if ($session->break_out_at && $session->break_in_at) {
            $breakMinutes = Carbon::parse($session->break_out_at)
                ->diffInMinutes(
                    Carbon::parse($session->break_in_at)
                );
    
            $workedMinutes -= $breakMinutes;
        } else {
            $breakMinutes = 0;
        }
    
        $session->update([
            'check_out_at' => now(),
            'worked_minutes' => max(0, $workedMinutes),
            'break_minutes' => $breakMinutes,
            'status' => 'completed',
        ]);
    
        return $session->fresh();
    }

    protected function ensureAuthorizedDevice(
        Internship $internship,
        string $macAddress
    ): void {

        $exists = Device::where('user_id', $internship->user_id)
            ->where('mac_address', $macAddress)
            ->where('is_authorized', true)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'device' => ['Appareil non autorisé.'],
            ]);
        }
    }
}