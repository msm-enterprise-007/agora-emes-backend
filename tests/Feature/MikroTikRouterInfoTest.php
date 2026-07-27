<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikRouterInfoTest extends TestCase
{
    public function test_can_get_router_info(): void
    {
        $service = app(MikroTikApiService::class);

        $info = $service->getRouterInfo();

        $this->assertIsArray($info);
    }
}