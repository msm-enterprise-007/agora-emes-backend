<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikPingTest extends TestCase
{
    public function test_ping_returns_boolean(): void
    {
        $service = app(MikroTikApiService::class);

        $this->assertIsBool(
            $service->isReachable()
        );
    }
}