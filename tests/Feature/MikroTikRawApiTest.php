<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use ReflectionClass;
use Tests\TestCase;

class MikroTikRawApiTest extends TestCase
{
    public function test_can_execute_raw_command(): void
    {
        $service = app(MikroTikApiService::class);

        $reflection = new ReflectionClass($service);

        $method = $reflection->getMethod('client');

        $method->setAccessible(true);

        $client = $method->invoke($service);

        $result = $client
            ->query('/system/resource/print')
            ->read();

        $this->assertIsArray($result);
    }
}