<?php

namespace Tests\Feature;

use App\Models\InternshipApplication;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternshipApplicationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_applications_index(): void
    {
        $response = $this->getJson('/api/internship-applications');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_applications_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/internship-applications');

        $response->assertOk();
    }

    public function test_authenticated_user_can_show_an_application(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $application = InternshipApplication::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson(
            "/api/internship-applications/{$application->id}"
        );

        $response->assertOk();

        $response->assertJsonFragment([
            'id' => $application->id,
        ]);
    }

    public function test_show_returns_404_for_unknown_application(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson(
            '/api/internship-applications/999999'
        );

        $response->assertNotFound();
    }

    public function test_authenticated_user_can_create_an_application(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson(
            '/api/internship-applications',
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'application_type' => 'internship',
                'education_level' => 'Licence 3',
                'motivation' => 'Je souhaite rejoindre votre entreprise.',
                'phone_mac_address' => 'AA:BB:CC:DD:EE:FF',
                'laptop_mac_address' => '11:22:33:44:55:66',
            ]
        );

        $response->assertCreated();

        $this->assertDatabaseHas(
            'internship_applications',
            [
                'email' => 'john@example.com',
            ]
        );
    }

    public function test_authenticated_user_can_reject_an_application(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $application = InternshipApplication::factory()->create();

        $this->actingAs($user);

        $response = $this->patchJson(
            "/api/internship-applications/{$application->id}/review",
            [
                'status' => 'rejected',
                'admin_comment' => 'Profil insuffisant.',
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas(
            'internship_applications',
            [
                'id' => $application->id,
                'status' => 'rejected',
            ]
        );
    }

    public function test_review_returns_404_for_unknown_application(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->patchJson(
            '/api/internship-applications/999999/review',
            [
                'status' => 'rejected',
                'admin_comment' => 'Refus.',
            ]
        );

        $response->assertNotFound();
    }
}