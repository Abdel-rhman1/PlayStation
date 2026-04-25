<?php

namespace App\Services\IoT;

use App\Models\Device;
use App\Services\IoT\Contracts\DeviceDriverInterface;
use App\Services\IoT\Drivers\SonoffHttpDriver;
use Illuminate\Support\Facades\Log;

class DeviceControlService
{
    /**
     * Issue an ON command to the physical relay.
     */
    public function turnOn(Device $device): bool
    {
        Log::info("IoT Queue: Dispatching ON command for device {$device->name}");
        \App\Jobs\ControlDeviceHardware::dispatch($device, 'on');
        return true;
    }

    /**
     * Issue an OFF command to the physical relay.
     */
    public function turnOff(Device $device): bool
    {
        Log::info("IoT Queue: Dispatching OFF command for device {$device->name}");
        \App\Jobs\ControlDeviceHardware::dispatch($device, 'off');
        return true;
    }

    /**
     * Resolve the appropriate driver for the device.
     */
    protected function resolveDriver(Device $device): DeviceDriverInterface
    {
        // This can be expanded to check device settings/metadata
        // match ($device->type) { ... }
        
        return app(SonoffHttpDriver::class);
    }
}
