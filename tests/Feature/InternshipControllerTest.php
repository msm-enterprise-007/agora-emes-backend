<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternshipControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_internships_index(): void
    {
        $response = $this->getJson('/api/internships');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_internships_index(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson('/api/internships');
    
        $response->assertOk();
    }

    public function test_authenticated_user_can_show_an_internship(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = \App\Models\Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson(
            "/api/internships/{$internship->id}"
        );
    
        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $internship->id,
        ]);
    }

    public function test_show_returns_404_for_unknown_internship(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson('/api/internships/999999');
    
        $response->assertNotFound();
    }

    public function test_authenticated_user_can_create_an_internship(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->postJson('/api/internships', [
            'user_id' => $user->id,
            'title' => 'Stage Développement Backend',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'work_start_time' => '08:00',
            'break_start_time' => '12:00',
            'break_end_time' => '13:00',
            'work_end_time' => '17:00',
            'authorized_absence_minutes' => 120,
            'status' => 'active',
            'description' => 'Stage de test',
        ]);
    
        $response->assertCreated();
    
        $this->assertDatabaseHas('internships', [
            'title' => 'Stage Développement Backend',
        ]);
    }

    public function test_authenticated_user_can_update_an_internship(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = \App\Models\Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->putJson("/api/internships/{$internship->id}", [
            'user_id' => $user->id,
            'title' => 'Stage Modifié',
            'start_date' => $internship->start_date->toDateString(),
            'end_date' => $internship->end_date->toDateString(),
            'work_start_time' => '09:00',
            'break_start_time' => '12:00',
            'break_end_time' => '13:00',
            'work_end_time' => '18:00',
            'authorized_absence_minutes' => 90,
            'status' => 'active',
            'description' => 'Description modifiée',
        ]);
    
        $response->assertOk();
    
        $this->assertDatabaseHas('internships', [
            'id' => $internship->id,
            'title' => 'Stage Modifié',
        ]);
    }

    public function test_authenticated_user_can_delete_an_internship(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $internship = \App\Models\Internship::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->deleteJson(
            "/api/internships/{$internship->id}"
        );
    
        $response->assertNoContent();
    
        $this->assertSoftDeleted('internships', [
            'id' => $internship->id,
        ]);
    }

    
}