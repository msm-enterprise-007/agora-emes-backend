<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikApiServiceIdentityTest extends TestCase
{
    public function test_get_identity(): void
    {
        $service = app(MikroTikApiService::class);

        $identity = $service->getIdentity();

        $this->assertIsString($identity);
        $this->assertNotEmpty($identity);
    }
}