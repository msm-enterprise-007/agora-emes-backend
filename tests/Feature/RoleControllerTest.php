<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_roles_index(): void
    {
        $response = $this->getJson('/api/roles');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_roles_index(): void
{
    $userRole = \App\Models\Role::factory()->create();

    $user = \App\Models\User::factory()->create([
        'role_id' => $userRole->id,
    ]);

    $this->actingAs($user);

    $response = $this->getJson('/api/roles');

    $response->assertOk();
}

public function test_authenticated_user_can_show_a_role(): void
{
    $userRole = \App\Models\Role::factory()->create();

    $user = \App\Models\User::factory()->create([
        'role_id' => $userRole->id,
    ]);

    $role = \App\Models\Role::factory()->create();

    $this->actingAs($user);

    $response = $this->getJson(
        "/api/roles/{$role->id}"
    );

    $response->assertOk();

    $response->assertJsonFragment([
        'id' => $role->id,
    ]);
}

public function test_show_returns_404_for_unknown_role(): void
{
    $userRole = \App\Models\Role::factory()->create();

    $user = \App\Models\User::factory()->create([
        'role_id' => $userRole->id,
    ]);

    $this->actingAs($user);

    $response = $this->getJson('/api/roles/999999');

    $response->assertNotFound();
}

public function test_authenticated_user_can_create_a_role(): void
{
    $userRole = \App\Models\Role::factory()->create();

    $user = \App\Models\User::factory()->create([
        'role_id' => $userRole->id,
    ]);

    $this->actingAs($user);

   $response = $this->postJson('/api/roles', [
    'name' => 'Responsable Réseau',
    'slug' => 'responsable-reseau',
    'description' => 'Responsable des stagiaires',
]);

    $response->assertCreated();

   $this->assertDatabaseHas('roles', [
    'name' => 'Responsable Réseau',
]);
}   

public function test_authenticated_user_can_update_a_role(): void
{
    $userRole = \App\Models\Role::factory()->create();

    $user = \App\Models\User::factory()->create([
        'role_id' => $userRole->id,
    ]);

    $role = \App\Models\Role::factory()->create();

    $this->actingAs($user);

    $response = $this->putJson("/api/roles/{$role->id}", [
        'name' => 'Administrateur',
        'slug' => 'administrateur',
        'description' => 'Accès complet',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'Administrateur',
    ]);
}

public function test_authenticated_user_can_delete_a_role(): void
{
    $userRole = \App\Models\Role::factory()->create();

    $user = \App\Models\User::factory()->create([
        'role_id' => $userRole->id,
    ]);

    $role = \App\Models\Role::factory()->create([
        'name' => 'Temporaire',
        'slug' => 'temporaire',
    ]);

    $this->actingAs($user);

    $response = $this->deleteJson(
        "/api/roles/{$role->id}"
    );

    $response->assertNoContent();

    $this->assertSoftDeleted('roles', [
        'id' => $role->id,
    ]);
}


}