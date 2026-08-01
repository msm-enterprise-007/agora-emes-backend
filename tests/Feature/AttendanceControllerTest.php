<?php

namespace Tests\Feature;

use App\Models\AttendanceSession;
use App\Models\Internship;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_attendance_index(): void
    {
        $this->getJson('/api/attendances')
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_attendance_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/attendances')
            ->assertOk();
    }

    public function test_authenticated_user_can_show_an_attendance(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $attendance = AttendanceSession::factory()->create();

        $this->actingAs($user);

        $this->getJson("/api/attendances/{$attendance->id}")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $attendance->id,
            ]);
    }

    public function test_show_returns_404_for_unknown_attendance(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/attendances/999999')
            ->assertNotFound();
    }

    public function test_authenticated_user_can_create_an_attendance(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->postJson('/api/attendances', [
            'internship_id' => $internship->id,
            'attendance_date' => now()->toDateString(),
            'status' => 'present',
            'is_verified' => false,
        ])->assertCreated();

        $this->assertDatabaseHas('attendance_sessions', [
            'internship_id' => $internship->id,
        ]);
    }

    public function test_authenticated_user_can_update_an_attendance(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $attendance = AttendanceSession::factory()->create();

        $this->actingAs($user);

        $this->putJson("/api/attendances/{$attendance->id}", [
            'internship_id' => $attendance->internship_id,
            'attendance_date' => $attendance->attendance_date->toDateString(),
            'status' => 'absent',
            'is_verified' => true,
        ])->assertOk();

        $this->assertDatabaseHas('attendance_sessions', [
            'id' => $attendance->id,
            'status' => 'absent',
        ]);
    }

    public function test_authenticated_user_can_delete_an_attendance(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $attendance = AttendanceSession::factory()->create();

        $this->actingAs($user);

        $this->deleteJson("/api/attendances/{$attendance->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('attendance_sessions', [
            'id' => $attendance->id,
        ]);
    }
}