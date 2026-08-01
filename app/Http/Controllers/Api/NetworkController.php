<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Network\Contracts\RouterInterface;
use Illuminate\Http\JsonResponse;

class NetworkController extends Controller
{
    public function __construct(
        protected RouterInterface $router
    ) {
    }

    public function info(): JsonResponse
    {
        return response()->json($this->router->getRouterInfo());
    }

    public function devices(): JsonResponse
    {
        return response()->json($this->router->getConnectedDevices());
    }

    public function block(string $mac): JsonResponse
    {
        return response()->json([
            'success' => $this->router->blockDevice($mac),
        ]);
    }

    public function authorize(string $mac): JsonResponse
    {
        return response()->json([
            'success' => $this->router->authorizeDevice($mac),
        ]);
    }

    public function leases(): JsonResponse
{
    return response()->json(
        $this->router->getDhcpLeases()
    );
}

public function arp(): JsonResponse
{
    return response()->json(
        $this->router->getArpTable()
    );
}

public function neighbors(): JsonResponse
{
    return response()->json(
        $this->router->getNeighbors()
    );
}

public function wirelessClients(): JsonResponse
{
    return response()->json(
        $this->router->getWirelessClients()
    );
}

public function hotspotUsers(): JsonResponse
{
    return response()->json(
        $this->router->getHotspotUsers()
    );
}

public function activeHotspotUsers(): JsonResponse
{
    return response()->json(
        $this->router->getActiveHotspotUsers()
    );
}

public function hotspotServers(): JsonResponse
{
    return response()->json(
        $this->router->getHotspotServers()
    );
}

public function health(): JsonResponse
{
    return response()->json(
        $this->router->getSystemHealth()
    );
}

public function clock(): JsonResponse
{
    return response()->json(
        $this->router->getSystemClock()
    );
}

public function dns(): JsonResponse
{
    return response()->json(
        $this->router->getDnsServers()
    );
}

public function ipAddresses(): JsonResponse
{
    return response()->json(
        $this->router->getIpAddresses()
    );
}

public function routes(): JsonResponse
{
    return response()->json(
        $this->router->getIpRoutes()
    );
}

public function firewallRules(): JsonResponse
{
    return response()->json(
        $this->router->getFirewallRules()
    );
}

public function bridgeHosts(): JsonResponse
{
    return response()->json(
        $this->router->getBridgeHosts()
    );
}

public function bridgePorts(): JsonResponse
{
    return response()->json(
        $this->router->getBridgePorts()
    );
}

public function logs(): JsonResponse
{
    return response()->json(
        $this->router->getLogs()
    );
}

public function reboot(): JsonResponse
{
    return response()->json([
        'success' => $this->router->reboot(),
    ]);
}

public function shutdown(): JsonResponse
{
    return response()->json([
        'success' => $this->router->shutdown(),
    ]);
}

public function backup(): JsonResponse
{
    return response()->json([
        'success' => $this->router->backup('agora-backup'),
    ]);
}

public function backups(): JsonResponse
{
    return response()->json(
        $this->router->getBackupFiles()
    );
}

public function status(): JsonResponse
{
    return response()->json([
        'reachable' => $this->router->isReachable(),
    ]);
}


}