<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_users_index(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_users_index(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson('/api/users');
    
        $response->assertOk();
    }

    public function test_authenticated_user_can_show_a_user(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $authUser = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($authUser);
    
        $response = $this->getJson(
            "/api/users/{$user->id}"
        );
    
        $response->assertOk();
        $response->assertJsonFragment([
            'email' => $user->email,
        ]);
    }

    public function test_show_returns_404_for_unknown_user(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson('/api/users/999999');
    
        $response->assertNotFound();
    }

    public function test_authenticated_user_can_create_a_user(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $authUser = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($authUser);
    
        $response = $this->postJson('/api/users', [
            'role_id' => $role->id,
            'matricule' => 'USR-99999',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '0102030405',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_active' => true,
        ]);
    
        $response->assertCreated();
    
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);
    }

    public function test_authenticated_user_can_update_a_user(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $authUser = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($authUser);
    
        $response = $this->putJson("/api/users/{$user->id}", [
            'role_id' => $role->id,
            'matricule' => $user->matricule,
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => $user->email,
            'phone' => '0700000000',
            'is_active' => true,
        ]);
    
        $response->assertOk();
    
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Jean',
        ]);
    }

    public function test_authenticated_user_can_delete_a_user(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $authUser = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($authUser);
    
        $response = $this->deleteJson("/api/users/{$user->id}");
    
        $response->assertNoContent();
    
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

}