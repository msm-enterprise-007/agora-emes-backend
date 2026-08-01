<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use ReflectionClass;
use Tests\TestCase;

class MikroTikDhcpLeasesTest extends TestCase
{
    public function test_can_get_dhcp_leases(): void
    {
        $service = app(MikroTikApiService::class);

        $reflection = new ReflectionClass($service);

        $method = $reflection->getMethod('client');

        $method->setAccessible(true);

        $client = $method->invoke($service);

        $result = $client
            ->query('/ip/dhcp-server/lease/print')
            ->read();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }
}