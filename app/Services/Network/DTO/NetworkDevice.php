<?php

namespace App\Services\Network\DTO;

class NetworkDevice
{
    public function __construct(
        public readonly string $macAddress,
        public readonly ?string $ipAddress = null,
        public readonly ?string $hostname = null,
        public readonly ?string $interface = null,
        public readonly ?int $signal = null,
        public readonly ?string $source = null,
        public readonly array $metadata = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'mac_address' => $this->macAddress,
            'ip_address' => $this->ipAddress,
            'hostname' => $this->hostname,
            'interface' => $this->interface,
            'signal' => $this->signal,
            'source' => $this->source,
            'metadata' => $this->metadata,
        ];
    }
}