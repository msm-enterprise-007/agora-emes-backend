<?php

namespace App\Services\Network\Contracts;

use App\Services\Network\DTO\NetworkDevice;

interface NetworkScannerInterface
{
    /**
     * Lance un scan réseau.
     *
     * @return NetworkDevice[]
     */
    public function scan(): array;
}