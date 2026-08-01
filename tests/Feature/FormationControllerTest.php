<?php

namespace Tests\Feature;

use App\Models\Formation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_formations_index(): void
    {
        $this->getJson('/api/formations')
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_formations_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/formations')
            ->assertOk();
    }

    public function test_authenticated_user_can_show_a_formation(): void
    {
        $role = Role::factory()->create();

        $supervisor = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $formation = Formation::create([
            'supervisor_id' => $supervisor->id,
            'title' => 'Laravel Avancé',
            'description' => 'Formation Laravel',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'capacity' => 20,
            'status' => 'planned',
        ]);

        $this->actingAs($user);

        $this->getJson("/api/formations/{$formation->id}")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $formation->id,
            ]);
    }

    public function test_show_returns_404_for_unknown_formation(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/formations/999999')
            ->assertNotFound();
    }

    public function test_authenticated_user_can_create_a_formation(): void
    {
        $role = Role::factory()->create();

        $supervisor = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/formations', [
            'supervisor_id' => $supervisor->id,
            'title' => 'Laravel Avancé',
            'description' => 'Formation Laravel',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'capacity' => 25,
            'status' => 'planned',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('formations', [
            'title' => 'Laravel Avancé',
        ]);
    }

    public function test_authenticated_user_can_update_a_formation(): void
    {
        $role = Role::factory()->create();

        $supervisor = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $formation = Formation::create([
            'supervisor_id' => $supervisor->id,
            'title' => 'Laravel',
            'description' => 'Initiale',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'capacity' => 20,
            'status' => 'planned',
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/formations/{$formation->id}", [
            'supervisor_id' => $supervisor->id,
            'title' => 'Laravel Expert',
            'description' => 'Formation mise à jour',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'capacity' => 30,
            'status' => 'ongoing',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('formations', [
            'id' => $formation->id,
            'title' => 'Laravel Expert',
            'status' => 'ongoing',
        ]);
    }

    public function test_authenticated_user_can_delete_a_formation(): void
    {
        $role = Role::factory()->create();

        $supervisor = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $formation = Formation::create([
            'supervisor_id' => $supervisor->id,
            'title' => 'Formation Test',
            'description' => null,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'capacity' => 15,
            'status' => 'planned',
        ]);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/formations/{$formation->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('formations', [
            'id' => $formation->id,
        ]);
    }
}