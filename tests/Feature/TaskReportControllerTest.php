<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Task;
use App\Models\TaskReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_task_reports_index(): void
    {
        $response = $this->getJson('/api/task-reports');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_task_reports_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/task-reports');

        $response->assertOk();
    }

    public function test_authenticated_user_can_show_a_task_report(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $report = TaskReport::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/api/task-reports/{$report->id}");

        $response->assertOk();

        $response->assertJsonFragment([
            'id' => $report->id,
        ]);
    }

    public function test_show_returns_404_for_unknown_task_report(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/task-reports/999999');

        $response->assertNotFound();
    }

    public function test_authenticated_user_can_create_a_task_report(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $task = Task::factory()->create([
            'assigned_by' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/task-reports', [
            'task_id' => $task->id,
            'version' => 1,
            'comment' => 'Premier rapport',
            'status' => 'submitted',
            'submitted_at' => now()->toDateTimeString(),
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('task_reports', [
            'comment' => 'Premier rapport',
        ]);
    }

    public function test_authenticated_user_can_update_a_task_report(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $report = TaskReport::factory()->create();

        $this->actingAs($user);

        $response = $this->putJson("/api/task-reports/{$report->id}", [
            'task_id' => $report->task_id,
            'version' => 2,
            'comment' => 'Rapport modifié',
            'status' => 'submitted',
            'submitted_at' => now()->toDateTimeString(),
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('task_reports', [
            'id' => $report->id,
            'version' => 2,
        ]);
    }

    public function test_authenticated_user_can_delete_a_task_report(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $report = TaskReport::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson("/api/task-reports/{$report->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('task_reports', [
            'id' => $report->id,
        ]);
    }
}