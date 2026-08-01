<?php

namespace App\Services\Network\Contracts;

use App\Services\Network\DTO\NetworkDevice;

interface RouterInterface
{
    /**
     * Retourne tous les appareils actuellement connectés.
     *
     * @return NetworkDevice[]
     */
    public function getConnectedDevices(): array;

    /**
     * Retourne les informations du routeur.
     */
    public function getRouterInfo(): array;

    /**
     * Vérifie que le routeur est joignable.
     */
    public function isReachable(): bool;


    
}