<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfigControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_configs_index(): void
    {
        $this->getJson('/api/configs')
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_configs_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/configs')
            ->assertOk();
    }

    public function test_authenticated_user_can_show_a_config(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $config = Config::create([
            'key' => 'app_name',
            'value' => 'Agora EMES',
            'type' => 'string',
            'description' => 'Nom de l’application',
        ]);

        $this->actingAs($user);

        $this->getJson("/api/configs/{$config->id}")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $config->id,
            ]);
    }

    public function test_show_returns_404_for_unknown_config(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/configs/999999')
            ->assertNotFound();
    }

    public function test_authenticated_user_can_create_a_config(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/configs', [
            'key' => 'mail_host',
            'value' => 'smtp.gmail.com',
            'type' => 'string',
            'description' => 'Serveur SMTP',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('configs', [
            'key' => 'mail_host',
        ]);
    }

    public function test_authenticated_user_can_update_a_config(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $config = Config::create([
            'key' => 'mail_host',
            'value' => 'localhost',
            'type' => 'string',
            'description' => 'Ancienne valeur',
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/configs/{$config->id}", [
            'key' => 'mail_host',
            'value' => 'smtp.office365.com',
            'type' => 'string',
            'description' => 'Nouvelle valeur',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('configs', [
            'id' => $config->id,
            'value' => 'smtp.office365.com',
        ]);
    }

    public function test_authenticated_user_can_delete_a_config(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $config = Config::create([
            'key' => 'temp_key',
            'value' => 'temp_value',
            'type' => 'string',
            'description' => 'Temporaire',
        ]);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/configs/{$config->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('configs', [
            'id' => $config->id,
        ]);
    }
}