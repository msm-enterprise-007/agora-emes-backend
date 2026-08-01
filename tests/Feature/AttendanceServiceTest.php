<?php

namespace Tests\Feature;

use App\Models\AttendanceSession;
use App\Models\Device;
use App\Models\Internship;
use App\Models\User;
use App\Services\Attendance\AttendanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_in_creates_attendance_session(): void
    {
        $role = \App\Models\Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
        
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => true,
        ]);

        $service = app(AttendanceService::class);

        $session = $service->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );

        $this->assertInstanceOf(
            AttendanceSession::class,
            $session
        );

        $this->assertNotNull(
            $session->check_in_at
        );
    }

    public function test_check_in_fails_with_unauthorized_device(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
    
        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => false,
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    }

    public function test_check_in_fails_when_internship_is_finished(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'start_date' => now()->subDays(30),
            'end_date' => now()->subDay(),
        ]);
    
        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => true,
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    }

    public function test_check_in_fails_when_internship_has_not_started(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(30),
        ]);
    
        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => true,
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    }

    public function test_check_in_fails_when_internship_is_not_active(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(10),
        ]);
    
        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => true,
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    }

    public function test_check_in_cannot_be_done_twice_on_same_day(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
    
        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => true,
        ]);
    
        $service = app(AttendanceService::class);
    
        $service->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        $service->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    }

    public function test_complete_attendance_workflow(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
    
        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => true,
        ]);
    
        $service = app(AttendanceService::class);
    
        $session = $service->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    
        $session = $service->breakOut($session);
    
        $this->assertNotNull($session->break_out_at);
    
        $session = $service->breakIn($session);
    
        $this->assertNotNull($session->break_in_at);
    
        $session = $service->checkOut($session);
    
        $this->assertNotNull($session->check_out_at);
    
        $this->assertEquals('completed', $session->status);
    }

    public function test_check_in_calculates_late_minutes(): void
    {
        $this->travelTo(now()->setTime(8, 30));
    
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'work_start_time' => '08:00:00',
        ]);
    
        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => true,
        ]);
    
        $session = app(AttendanceService::class)->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    
        $this->assertEquals(30, $session->late_minutes);
        $this->assertEquals('late', $session->arrival_status);
    
        $this->travelBack();
    }

    public function test_check_in_on_time_sets_arrival_status_to_on_time(): void
    {
        $this->travelTo(now()->setTime(7, 55));
    
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'work_start_time' => '08:00:00',
        ]);
    
        Device::factory()->create([
            'user_id' => $user->id,
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'is_authorized' => true,
        ]);
    
        $session = app(AttendanceService::class)->checkIn(
            $internship,
            'AA:BB:CC:DD:EE:FF'
        );
    
        $this->assertEquals(0, $session->late_minutes);
        $this->assertEquals('on_time', $session->arrival_status);
    
        $this->travelBack();
    }

    public function test_break_out_requires_check_in(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $session = AttendanceSession::factory()->create([
            'internship_id' => $internship->id,
            'check_in_at' => null,
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->breakOut($session);
    }

    public function test_break_in_requires_break_out(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $session = AttendanceSession::factory()->create([
            'internship_id' => $internship->id,
            'check_in_at' => now(),
            'break_out_at' => null,
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->breakIn($session);
    }

    public function test_check_out_requires_check_in(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $session = AttendanceSession::factory()->create([
            'internship_id' => $internship->id,
            'check_in_at' => null,
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->checkOut($session);
    }

    public function test_check_out_cannot_be_done_twice(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $session = AttendanceSession::factory()->create([
            'internship_id' => $internship->id,
            'check_in_at' => now()->subHours(8),
            'check_out_at' => now(),
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->checkOut($session);
    }

    public function test_break_out_cannot_be_done_twice(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $session = AttendanceSession::factory()->create([
            'internship_id' => $internship->id,
            'check_in_at' => now()->subHour(),
            'break_out_at' => now(),
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->breakOut($session);
    }

    public function test_break_in_cannot_be_done_twice(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $session = AttendanceSession::factory()->create([
            'internship_id' => $internship->id,
            'check_in_at' => now()->subHours(2),
            'break_out_at' => now()->subHour(),
            'break_in_at' => now()->subMinutes(30),
        ]);
    
        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );
    
        app(AttendanceService::class)->breakIn($session);
    }
}