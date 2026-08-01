<?php

namespace App\Services\Network;

use App\Services\Network\Contracts\RouterInterface;
use App\Services\Network\DTO\NetworkDevice;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

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
        $arpEntries = $this->getArpTable();

        $devices = [];

        foreach ($arpEntries as $entry) {
            if (
                empty($entry['mac-address']) ||
                ($entry['status'] ?? '') !== 'reachable'
            ) {
                continue;
            }

            $devices[] = new NetworkDevice(
                macAddress: $entry['mac-address'],
                ipAddress: $entry['address'] ?? null,
                hostName: null,
                vendor: null,
                signal: null,
                connected: true,
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
        $result = $this->client()
            ->query('/system/resource/print')
            ->read();

        return $result[0] ?? [];
    } catch (\Throwable $e) {
        return [];
    }
}

    public function getIdentity(): string
    {
        try {
            $result = $this->client()
                ->query('/system/identity/print')
                ->read();
    
            return $result[0]['name'] ?? '';
        } catch (\Throwable $e) {
            return '';
        }
    }

    public function isReachable(): bool
    {
        try {
            $this->client()
                ->query('/system/resource/print')
                ->read();
    
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function ping(): bool
    {
        return $this->isReachable();
    }
    
    public function getInterfaces(): array
    {
        try {
            return $this->client()
                ->query('/interface/print')
                ->read();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function blockDevice(string $macAddress): bool
{
    try {
        $query = (new Query('/ip/firewall/filter/add'))
            ->equal('chain', 'forward')
            ->equal('src-mac-address', $macAddress)
            ->equal('action', 'drop')
            ->equal('comment', 'AGORA');

        $this->client()->query($query)->read();

        return true;
    } catch (\Throwable $e) {
        return false;
    }
}

public function authorizeDevice(string $macAddress): bool
{
    try {
        $rules = $this->client()
            ->query('/ip/firewall/filter/print')
            ->read();

        foreach ($rules as $rule) {
            if (
                ($rule['src-mac-address'] ?? '') === $macAddress &&
                ($rule['comment'] ?? '') === 'AGORA'
            ) {
                $this->client()
                    ->query(
                        (new Query('/ip/firewall/filter/remove'))
                            ->equal('.id', $rule['.id'])
                    )
                    ->read();
            }
        }

        return true;
    } catch (\Throwable $e) {
        return false;
    }
}

public function getDhcpLeases(): array
{
    try {
        return $this->client()
            ->query('/ip/dhcp-server/lease/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getArpTable(): array
{
    try {
        return $this->client()
            ->query('/ip/arp/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}


public function arp(): JsonResponse
{
    return response()->json(
        $this->router->getArpTable()
    );
}

public function getNeighbors(): array
{
    try {
        return $this->client()
            ->query('/ip/neighbor/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getWirelessClients(): array
{
    try {
        return $this->client()
            ->query('/interface/wifi/registration-table/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getHotspotUsers(): array
{
    try {
        return $this->client()
            ->query('/ip/hotspot/user/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}


public function getActiveHotspotUsers(): array
{
    try {
        return $this->client()
            ->query('/ip/hotspot/active/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getHotspotServers(): array
{
    try {
        return $this->client()
            ->query('/ip/hotspot/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getSystemHealth(): array
{
    try {
        return $this->client()
            ->query('/system/health/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getSystemClock(): array
{
    try {
        return $this->client()
            ->query('/system/clock/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getDnsServers(): array
{
    try {
        return $this->client()
            ->query('/ip/dns/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getIpAddresses(): array
{
    try {
        return $this->client()
            ->query('/ip/address/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getIpRoutes(): array
{
    try {
        return $this->client()
            ->query('/ip/route/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getFirewallRules(): array
{
    try {
        return $this->client()
            ->query('/ip/firewall/filter/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getBridgeHosts(): array
{
    try {
        return $this->client()
            ->query('/interface/bridge/host/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getBridgePorts(): array
{
    try {
        return $this->client()
            ->query('/interface/bridge/port/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function getLogs(): array
{
    try {
        return $this->client()
            ->query('/log/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

public function reboot(): bool
{
    try {
        $this->client()
            ->query('/system/reboot')
            ->read();

        return true;
    } catch (\Throwable $e) {
        return false;
    }
}

public function shutdown(): bool
{
    try {
        $this->client()
            ->query('/system/shutdown')
            ->read();

        return true;
    } catch (\Throwable $e) {
        return false;
    }
}

public function backup(string $name): bool
{
    try {
        $query = (new Query('/system/backup/save'))
            ->equal('name', $name);

        $this->client()
            ->query($query)
            ->read();

        return true;
    } catch (\Throwable $e) {
        return false;
    }
}

public function getBackupFiles(): array
{
    try {
        return $this->client()
            ->query('/file/print')
            ->read();
    } catch (\Throwable $e) {
        return [];
    }
}

}