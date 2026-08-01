<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_devices_index(): void
    {
        $response = $this->getJson('/api/devices');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_devices_index(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson('/api/devices');
    
        $response->assertOk();
    }

    public function test_authenticated_user_can_show_a_device(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $device = \App\Models\Device::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson(
            "/api/devices/{$device->id}"
        );
    
        $response->assertOk();
    
        $response->assertJsonFragment([
            'id' => $device->id,
        ]);
    }

    public function test_show_returns_404_for_unknown_device(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson('/api/devices/999999');
    
        $response->assertNotFound();
    }

    public function test_authenticated_user_can_create_a_device(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->postJson('/api/devices', [
            'user_id' => $user->id,
            'device_name' => 'Laptop HP',
            'device_type' => 'laptop',
            'mac_address' => 'AA:BB:CC:DD:EE:99',
            'ip_address' => '192.168.1.10',
            'manufacturer' => 'HP',
            'is_authorized' => true,
        ]);
    
        $response->assertCreated();
    
        $this->assertDatabaseHas('devices', [
            'mac_address' => 'AA:BB:CC:DD:EE:99',
        ]);
    }

    public function test_authenticated_user_can_update_a_device(): void
    {
        $role = \App\Models\Role::factory()->create();
    
        $user = \App\Models\User::factory()->create([
            'role_id' => $role->id,
        ]);
    
        $device = \App\Models\Device::factory()->create([
            'user_id' => $user->id,
        ]);
    
        $this->actingAs($user);
    
        $response = $this->putJson("/api/devices/{$device->id}", [
            'user_id' => $user->id,
            'device_name' => 'PC Bureau',
            'device_type' => 'desktop',
            'mac_address' => $device->mac_address,
            'ip_address' => '192.168.1.20',
            'manufacturer' => 'Dell',
            'is_authorized' => false,
        ]);
    
        $response->assertOk();
    
        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'device_name' => 'PC Bureau',
            'is_authorized' => false,
        ]);
    }

    public function test_authenticated_user_can_delete_a_device(): void
{
    $role = \App\Models\Role::factory()->create();

    $user = \App\Models\User::factory()->create([
        'role_id' => $role->id,
    ]);

    $device = \App\Models\Device::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $response = $this->deleteJson(
        "/api/devices/{$device->id}"
    );

    $response->assertNoContent();

    $this->assertSoftDeleted('devices', [
        'id' => $device->id,
    ]);
}
}