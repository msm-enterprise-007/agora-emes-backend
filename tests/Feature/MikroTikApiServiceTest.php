<?php

namespace Tests\Feature;

use App\Services\Network\MikroTikApiService;
use Tests\TestCase;

class MikroTikApiServiceTest extends TestCase
{
    public function test_service_can_be_instantiated(): void
    {
        $service = app(MikroTikApiService::class);

        $this->assertInstanceOf(
            MikroTikApiService::class,
            $service
        );
    }
}