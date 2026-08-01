<?php

namespace App\Services\Network;

use App\Services\Network\Contracts\OlaxInterface;

class OlaxApiService implements OlaxInterface
{
    protected string $host;
    protected string $username;
    protected string $password;
    protected int $timeout;

    public function __construct()
    {
        $this->host = config('network.olax.host');
        $this->username = config('network.olax.username');
        $this->password = config('network.olax.password');
        $this->timeout = (int) config('network.olax.timeout');
    }

    public function getConnectedDevices(): array
    {
        return [];
    }
}