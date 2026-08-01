<?php

namespace Tests\Feature;

use App\Services\Network\Contracts\OlaxInterface;
use Tests\TestCase;

class OlaxServiceTest extends TestCase
{
    public function test_olax_service_is_registered(): void
    {
        $service = app(OlaxInterface::class);

        $this->assertInstanceOf(
            OlaxInterface::class,
            $service
        );
    }
}