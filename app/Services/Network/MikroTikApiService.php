<?php

namespace App\Services\Network;

use App\Services\Network\Contracts\RouterInterface;
use App\Services\Network\DTO\NetworkDevice;

class MikroTikApiService implements RouterInterface
{
    /**
     * @return NetworkDevice[]
     */
    public function connectedDevices(): array
    {
        return [];
    }

    public function authorizeDevice(string $macAddress): bool
    {
        return false;
    }

    public function blockDevice(string $macAddress): bool
    {
        return false;
    }
}