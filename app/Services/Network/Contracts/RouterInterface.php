<?php

namespace App\Services\Network\Contracts;

use App\Services\Network\DTO\NetworkDevice;

interface RouterInterface
{
    /**
     * Vérifie que le routeur est joignable.
     */
    public function ping(): bool;

    /**
     * Retourne les appareils connectés au routeur.
     *
     * @return NetworkDevice[]
     */
    public function connectedDevices(): array;
}