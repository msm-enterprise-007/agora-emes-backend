<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikConnectionTest extends TestCase
{
    public function test_mikrotik_is_reachable(): void
    {
        $service = app(MikroTikApiService::class);

        $this->assertIsBool(
            $service->isReachable()
        );
    }
}