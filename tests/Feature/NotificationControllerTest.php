<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_notifications_index(): void
    {
        $this->getJson('/api/notifications')
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_notifications_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/notifications')
            ->assertOk();
    }

    public function test_authenticated_user_can_show_a_notification(): void
    {
        $role = Role::factory()->create();

        $owner = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $notification = Notification::create([
            'user_id' => $owner->id,
            'title' => 'Bienvenue',
            'message' => 'Votre compte est actif.',
            'type' => 'info',
            'is_read' => false,
        ]);

        $this->actingAs($user);

        $this->getJson("/api/notifications/{$notification->id}")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $notification->id,
            ]);
    }

    public function test_show_returns_404_for_unknown_notification(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/notifications/999999')
            ->assertNotFound();
    }

    public function test_authenticated_user_can_create_a_notification(): void
    {
        $role = Role::factory()->create();

        $owner = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/notifications', [
            'user_id' => $owner->id,
            'title' => 'Nouvelle notification',
            'message' => 'Votre stage est validé.',
            'type' => 'success',
            'is_read' => false,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('notifications', [
            'title' => 'Nouvelle notification',
            'type' => 'success',
        ]);
    }

    public function test_authenticated_user_can_update_a_notification(): void
    {
        $role = Role::factory()->create();

        $owner = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $notification = Notification::create([
            'user_id' => $owner->id,
            'title' => 'Ancien titre',
            'message' => 'Ancien message',
            'type' => 'info',
            'is_read' => false,
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/notifications/{$notification->id}", [
            'user_id' => $owner->id,
            'title' => 'Titre modifié',
            'message' => 'Message modifié',
            'type' => 'warning',
            'is_read' => true,
            'read_at' => now()->toDateTimeString(),
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'title' => 'Titre modifié',
            'type' => 'warning',
            'is_read' => true,
        ]);
    }

    public function test_authenticated_user_can_delete_a_notification(): void
    {
        $role = Role::factory()->create();

        $owner = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $notification = Notification::create([
            'user_id' => $owner->id,
            'title' => 'À supprimer',
            'message' => 'Suppression',
            'type' => 'info',
            'is_read' => false,
        ]);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/notifications/{$notification->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
        ]);
    }
}