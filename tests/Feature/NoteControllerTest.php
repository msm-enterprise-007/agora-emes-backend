<?php

namespace Tests\Feature;

use App\Models\Internship;
use App\Models\Note;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_notes_index(): void
    {
        $response = $this->getJson('/api/notes');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_notes_index(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/notes');

        $response->assertOk();
    }

    public function test_authenticated_user_can_show_a_note(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $note = Note::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/api/notes/{$note->id}");

        $response->assertOk();

        $response->assertJsonFragment([
            'id' => $note->id,
        ]);
    }

    public function test_show_returns_404_for_unknown_note(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/notes/999999');

        $response->assertNotFound();
    }

    public function test_authenticated_user_can_create_a_note(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/notes', [
            'internship_id' => $internship->id,
            'author_id' => $user->id,
            'title' => 'Observation',
            'content' => 'Très bon travail.',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('notes', [
            'title' => 'Observation',
        ]);
    }

    public function test_authenticated_user_can_update_a_note(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $internship = Internship::factory()->create([
            'user_id' => $user->id,
        ]);

        $note = Note::factory()->create([
            'internship_id' => $internship->id,
            'author_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/notes/{$note->id}", [
            'internship_id' => $internship->id,
            'author_id' => $user->id,
            'title' => 'Observation modifiée',
            'content' => 'Excellent travail.',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Observation modifiée',
        ]);
    }

    public function test_authenticated_user_can_delete_a_note(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $note = Note::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson("/api/notes/{$note->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('notes', [
            'id' => $note->id,
        ]);
    }
}