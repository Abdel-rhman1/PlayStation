<?php

namespace App\Services\IoT\Contracts;

use App\Models\Device;

interface DeviceDriverInterface
{
    /**
     * Send hardware ON command to device
     */
    public function turnOn(Device $device): bool;

    /**
     * Send hardware OFF command to device
     */
    public function turnOff(Device $device): bool;
}
