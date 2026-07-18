<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Internship;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\AttendanceSession;

class AttendanceActionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_in_endpoint_returns_200(): void
    {
        $role = Role::factory()->create();

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

        $this->actingAs($user);

        $response = $this->postJson(
            "/api/internships/{$internship->id}/check-in",
            [
                'mac_address' => 'AA:BB:CC:DD:EE:FF',
            ]
        );

        $response->assertStatus(200);
    }

    public function test_check_in_requires_mac_address(): void
    {
        $role = Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->postJson(
            "/api/internships/{$internship->id}/check-in",
            []
        );
    
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'mac_address',
            ]);
    }

    public function test_check_in_returns_422_for_unauthorized_device(): void
    {
        $role = Role::factory()->create();
    
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
    
        $this->actingAs($user);
    
        $response = $this->postJson(
            "/api/internships/{$internship->id}/check-in",
            [
                'mac_address' => 'AA:BB:CC:DD:EE:FF',
            ]
        );
    
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'device',
            ]);
    }

    public function test_guest_cannot_check_in(): void
    {
        $internship = Internship::factory()->create();
    
        $response = $this->postJson(
            "/api/internships/{$internship->id}/check-in",
            [
                'mac_address' => 'AA:BB:CC:DD:EE:FF',
            ]
        );
    
        $response->assertUnauthorized();
    }

    public function test_break_out_endpoint_returns_200(): void
    {
        $role = Role::factory()->create();
    
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
    
        $this->actingAs($user);
    
        $this->postJson(
            "/api/internships/{$internship->id}/check-in",
            [
                'mac_address' => 'AA:BB:CC:DD:EE:FF',
            ]
        )->assertOk();
    
        $session = AttendanceSession::where(
            'internship_id',
            $internship->id
        )->first();
        
        $response = $this->postJson(
            "/api/attendance-sessions/{$session->id}/break-out"
        );
    
        $response->assertOk();
    }

    public function test_break_in_endpoint_returns_200(): void
    {
        $role = Role::factory()->create();
    
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
    
        $this->actingAs($user);
    
        $this->postJson(
            "/api/internships/{$internship->id}/check-in",
            [
                'mac_address' => 'AA:BB:CC:DD:EE:FF',
            ]
        )->assertOk();
    
        $session = AttendanceSession::where(
            'internship_id',
            $internship->id
        )->first();
    
        $this->postJson(
            "/api/attendance-sessions/{$session->id}/break-out"
        )->assertOk();
    
        $response = $this->postJson(
            "/api/attendance-sessions/{$session->id}/break-in"
        );
    
        $response->assertOk();
    }

    public function test_check_out_endpoint_returns_200(): void
    {
        $role = Role::factory()->create();
    
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
    
        $this->actingAs($user);
    
        $this->postJson(
            "/api/internships/{$internship->id}/check-in",
            [
                'mac_address' => 'AA:BB:CC:DD:EE:FF',
            ]
        )->assertOk();
    
        $session = AttendanceSession::where(
            'internship_id',
            $internship->id
        )->first();
    
        $this->postJson(
            "/api/attendance-sessions/{$session->id}/break-out"
        )->assertOk();
    
        $this->postJson(
            "/api/attendance-sessions/{$session->id}/break-in"
        )->assertOk();
    
        $response = $this->postJson(
            "/api/attendance-sessions/{$session->id}/check-out"
        );
    
        $response->assertOk();
    }

}