<?php

namespace Tests\Feature;

use App\Models\Internship;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_tasks_index(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_tasks_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/tasks');

        $response->assertOk();
    }

    public function test_authenticated_user_can_show_a_task(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $task = Task::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertOk();

        $response->assertJsonFragment([
            'id' => $task->id,
        ]);
    }

    public function test_show_returns_404_for_unknown_task(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/tasks/999999');

        $response->assertNotFound();
    }

    public function test_authenticated_user_can_create_a_task(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/tasks', [
            'internship_id' => $internship->id,
            'assigned_by' => $user->id,
            'title' => 'Configurer le routeur',
            'description' => 'Installation MikroTik',
            'due_date' => now()->addDay()->toDateString(),
            'priority' => 'high',
            'status' => 'pending',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Configurer le routeur',
        ]);
    }

    public function test_authenticated_user_can_update_a_task(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);

        $task = Task::factory()->create([
            'internship_id' => $internship->id,
            'assigned_by' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'internship_id' => $internship->id,
            'assigned_by' => $user->id,
            'title' => 'Configurer MikroTik',
            'description' => 'Configuration terminée',
            'due_date' => now()->addDays(2)->toDateString(),
            'priority' => 'medium',
            'status' => 'validated',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Configurer MikroTik',
        ]);
    }

    public function test_authenticated_user_can_delete_a_task(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $task = Task::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }
}