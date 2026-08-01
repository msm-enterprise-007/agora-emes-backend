<?php

namespace Tests\Feature;

use App\Services\Network\DTO\NetworkDevice;
use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikConnectedDevicesServiceTest extends TestCase
{
    public function test_get_connected_devices(): void
    {
        $service = app(MikroTikApiService::class);

        $devices = $service->getConnectedDevices();

        $this->assertIsArray($devices);

        if (!empty($devices)) {
            $this->assertInstanceOf(NetworkDevice::class, $devices[0]);
        }
    }
}