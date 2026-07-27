<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikConnectedDevicesTest extends TestCase
{
    public function test_can_get_connected_devices(): void
    {
        $service = app(MikroTikApiService::class);

        $devices = $service->getConnectedDevices();

        $this->assertIsArray($devices);
    }
}