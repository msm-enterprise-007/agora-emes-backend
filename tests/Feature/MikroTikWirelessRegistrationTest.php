<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use ReflectionClass;
use Tests\TestCase;

class MikroTikWirelessRegistrationTest extends TestCase
{
    public function test_can_get_wireless_registration_table(): void
    {
        $service = app(MikroTikApiService::class);

        $reflection = new ReflectionClass($service);

        $method = $reflection->getMethod('client');

        $method->setAccessible(true);

        $client = $method->invoke($service);

        $result = $client
            ->query('/interface/wifi/registration-table/print')
            ->read();

        $this->assertIsArray($result);
    }
}   