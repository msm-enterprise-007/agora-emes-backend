<?php

namespace App\Services\Network;

use App\Services\Network\Contracts\NetworkScannerInterface;
use App\Services\Network\DTO\NetworkDevice;

class SnmpService implements NetworkScannerInterface
{
    /**
     * @return NetworkDevice[]
     */
    public function scan(): array
    {
        return [];
    }
}