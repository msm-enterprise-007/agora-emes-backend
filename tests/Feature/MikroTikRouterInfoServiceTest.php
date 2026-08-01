<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikRouterInfoServiceTest extends TestCase
{
    public function test_get_router_info(): void
    {
        $service = app(MikroTikApiService::class);

        $info = $service->getRouterInfo();

        $this->assertArrayHasKey('board-name', $info);
        $this->assertArrayHasKey('version', $info);
        $this->assertArrayHasKey('cpu-load', $info);
        $this->assertArrayHasKey('uptime', $info);
    }
}