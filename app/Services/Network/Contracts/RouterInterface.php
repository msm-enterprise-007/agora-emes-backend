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

    public function blockDevice(string $macAddress): bool;

    public function authorizeDevice(string $macAddress): bool;

    public function getDhcpLeases(): array;

    public function getArpTable(): array;

    public function getNeighbors(): array;

    public function getWirelessClients(): array;

    public function getHotspotUsers(): array;
    
    public function getActiveHotspotUsers(): array;

    public function getHotspotServers(): array;

    public function getSystemHealth(): array;

    public function getSystemClock(): array;

    public function getDnsServers(): array;

    public function getIpAddresses(): array;

    public function getIpRoutes(): array;

    public function getFirewallRules(): array;

    public function getBridgeHosts(): array;

    public function getBridgePorts(): array;

    public function getLogs(): array;

    public function reboot(): bool;

    public function shutdown(): bool;

    public function backup(string $name): bool;

    public function getBackupFiles(): array;    
}