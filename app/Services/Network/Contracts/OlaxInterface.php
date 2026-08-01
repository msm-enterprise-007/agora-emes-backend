<?php

namespace App\Services\Network\Contracts;

interface OlaxInterface
{
    public function getConnectedDevices(): array;
}