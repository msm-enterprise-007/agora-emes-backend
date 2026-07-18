<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login(): void
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'token',
            'user',
        ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $role = Role::factory()->create();
    
        User::factory()->create([
            'role_id' => $role->id,
            'password' => Hash::make('password'),
        ]);
    
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);
    
        $response->assertStatus(422);
    }

    public function test_user_can_logout(): void
    {
        $role = Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $token = $user->createToken('test-token')->plainTextToken;
    
        $response = $this->withHeader(
            'Authorization',
            'Bearer '.$token
        )->postJson('/api/logout');
    
        $response->assertOk();
    }
    
    public function test_guest_cannot_logout(): void
    {
        $response = $this->postJson('/api/logout');
    
        $response->assertUnauthorized();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $role = Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => Hash::make('password'),
            'is_active' => false,
        ]);
    
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
    
        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_get_his_profile(): void
    {
        $role = Role::factory()->create();
    
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $token = $user->createToken('test-token')->plainTextToken;
    
        $response = $this->withHeader(
            'Authorization',
            'Bearer '.$token
        )->getJson('/api/user');
    
        $response->assertOk();
        $response->assertJsonFragment([
            'email' => $user->email,
        ]);
    }
}