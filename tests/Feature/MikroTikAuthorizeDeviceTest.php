<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikAuthorizeDeviceTest extends TestCase
{
    public function test_authorize_device_returns_boolean(): void
    {
        $service = app(MikroTikApiService::class);

        $result = $service->authorizeDevice('00:11:22:33:44:55');

        $this->assertIsBool($result);
    }
}