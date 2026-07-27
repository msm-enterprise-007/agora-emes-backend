<?php

namespace App\Services\Network;

class MikroTikApiClient
{
    protected string $host;
    protected string $username;
    protected string $password;
    protected int $port;
    protected int $timeout;

    public function __construct()
    {
        $this->host = config('network.mikrotik.host');
        $this->username = config('network.mikrotik.username');
        $this->password = config('network.mikrotik.password');
        $this->port = config('network.mikrotik.port');
        $this->timeout = config('network.mikrotik.timeout');
    }

    public function connect(): bool
    {
        return false;
    }

    public function disconnect(): void
    {
    }

    public function command(string $command, array $parameters = []): array
    {
        return [];
    }
}