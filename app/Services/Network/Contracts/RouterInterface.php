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
     * Retourne les informations du routeur.
     */
    public function getRouterInfo(): array;

    /**
     * Retourne le nom du routeur.
     */
    public function getIdentity(): string;

    /**
     * Retourne les interfaces réseau.
     */
    public function getInterfaces(): array;

    /**
     * Retourne les appareils connectés.
     *
     * @return NetworkDevice[]
     */
    public function getConnectedDevices(): array;
}