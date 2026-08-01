<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_audit_logs_index(): void
    {
        $this->getJson('/api/audit-logs')
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_audit_logs_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/audit-logs')
            ->assertOk();
    }

    public function test_authenticated_user_can_show_an_audit_log(): void
    {
        $role = Role::factory()->create();

        $owner = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $auditLog = AuditLog::create([
            'user_id' => $owner->id,
            'action' => 'CREATE',
            'entity_type' => 'User',
            'entity_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'old_values' => [],
            'new_values' => ['name' => 'John'],
            'performed_at' => now(),
        ]);

        $this->actingAs($user);

        $this->getJson("/api/audit-logs/{$auditLog->id}")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $auditLog->id,
            ]);
    }

    public function test_show_returns_404_for_unknown_audit_log(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/audit-logs/999999')
            ->assertNotFound();
    }

    public function test_authenticated_user_can_create_an_audit_log(): void
    {
        $role = Role::factory()->create();

        $owner = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/audit-logs', [
            'user_id' => $owner->id,
            'action' => 'CREATE',
            'entity_type' => 'Formation',
            'entity_id' => 10,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'old_values' => [],
            'new_values' => [
                'title' => 'Laravel',
            ],
            'performed_at' => now()->toDateTimeString(),
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'CREATE',
            'entity_type' => 'Formation',
        ]);
    }

    public function test_authenticated_user_can_update_an_audit_log(): void
    {
        $role = Role::factory()->create();

        $owner = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $auditLog = AuditLog::create([
            'user_id' => $owner->id,
            'action' => 'CREATE',
            'entity_type' => 'Formation',
            'entity_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'old_values' => [],
            'new_values' => [],
            'performed_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/audit-logs/{$auditLog->id}", [
            'user_id' => $owner->id,
            'action' => 'UPDATE',
            'entity_type' => 'Formation',
            'entity_id' => 1,
            'ip_address' => '192.168.1.10',
            'user_agent' => 'Laravel Test',
            'old_values' => [
                'title' => 'Old',
            ],
            'new_values' => [
                'title' => 'New',
            ],
            'performed_at' => now()->toDateTimeString(),
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('audit_logs', [
            'id' => $auditLog->id,
            'action' => 'UPDATE',
            'ip_address' => '192.168.1.10',
        ]);
    }

    public function test_authenticated_user_can_delete_an_audit_log(): void
    {
        $role = Role::factory()->create();

        $owner = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $auditLog = AuditLog::create([
            'user_id' => $owner->id,
            'action' => 'DELETE',
            'entity_type' => 'User',
            'entity_id' => 5,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'old_values' => [],
            'new_values' => [],
            'performed_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/audit-logs/{$auditLog->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('audit_logs', [
            'id' => $auditLog->id,
        ]);
    }
}