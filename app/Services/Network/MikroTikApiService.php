<?php

namespace App\Services\Network;

use App\Services\Network\Contracts\RouterInterface;
use App\Services\Network\DTO\NetworkDevice;
use RouterOS\Client;
use RouterOS\Config;

class MikroTikApiService implements RouterInterface
{
    protected ?Client $client = null;

    // public function __construct()
    // {
        // $config = new Config([
        //     'host' => config('network.mikrotik.host'),
        //     'user' => config('network.mikrotik.username'),
        //     'pass' => config('network.mikrotik.password'),
        //     'port' => (int) config('network.mikrotik.port'),
        // ]);

        // $this->client = new Client($config);
    // }
         private function client(): Client
        {
            if ($this->client === null) {
                $config = new Config([
                    'host' => config('network.mikrotik.host'),
                    'user' => config('network.mikrotik.username'),
                    'pass' => config('network.mikrotik.password'),
                    'port' => (int) config('network.mikrotik.port'),
                ]);
        
                $this->client = new Client($config);
            }
        
            return $this->client;
        }

   

    /**
     * @return NetworkDevice[]
     */
    public function getConnectedDevices(): array
    {
        try {
            $leases = $this->client()
                ->query('/ip/dhcp-server/lease/print')
                ->read();
    
            $devices = [];
    
            foreach ($leases as $lease) {
                $devices[] = new NetworkDevice(
                    macAddress: $lease['mac-address'] ?? '',
                    ipAddress: $lease['address'] ?? null,
                    hostName: $lease['host-name'] ?? null,
                    vendor: null,
                    signal: null,
                    connected: ($lease['status'] ?? '') === 'bound',
                );
            }
    
            return $devices;
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getRouterInfo(): array
    {
        try {
            return $this->client()
                ->query('/system/resource/print')
                ->read();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function isReachable(): bool
    {
        try {
            $this->client->query('/system/resource/print')->read();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}