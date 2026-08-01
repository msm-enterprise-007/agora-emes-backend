<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikInterfacesServiceTest extends TestCase
{
    public function test_get_interfaces(): void
    {
        $service = app(MikroTikApiService::class);

        $interfaces = $service->getInterfaces();

        $this->assertIsArray($interfaces);
        $this->assertNotEmpty($interfaces);
    }
}