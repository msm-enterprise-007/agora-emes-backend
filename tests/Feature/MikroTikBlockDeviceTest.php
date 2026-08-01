<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikBlockDeviceTest extends TestCase
{
    public function test_block_unknown_device_returns_boolean(): void
    {
        $service = app(MikroTikApiService::class);

        $result = $service->blockDevice('00:11:22:33:44:55');

        $this->assertIsBool($result);
    }
}