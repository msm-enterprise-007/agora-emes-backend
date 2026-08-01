<?php

namespace App\Services\Network;

use App\Services\Network\Contracts\NetworkScannerInterface;
use App\Services\Network\Contracts\RouterInterface;

class NetworkDetectionService
{
    public function __construct(
        protected NetworkScannerInterface $scanner,
        protected RouterInterface $router,
    ) {
    }

    public function discover(): array
    {
        return $this->scanner->scan();
    }

    public function connectedDevices(): array
    {
        return $this->router->connectedDevices();
    }

    public function authorize(string $macAddress): bool
    {
        return $this->router->authorizeDevice($macAddress);
    }

    public function block(string $macAddress): bool
    {
        return $this->router->blockDevice($macAddress);
    }
}