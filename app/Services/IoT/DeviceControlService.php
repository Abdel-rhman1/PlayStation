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
        Log::info("IoT Action: Sending ON command to device {$device->name} ({$device->id})");

        try {
            $driver = $this->resolveDriver($device);
            $success = $driver->turnOn($device);

            if ($success) {
                Log::info("IoT Success: Device {$device->name} turned ON.");
            } else {
                Log::error("IoT Failure: Driver failed to turn ON device {$device->name}.");
            }

            return $success;
        } catch (\Throwable $e) {
            Log::critical("IoT Error: Exception during turnOn for device {$device->name}", [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Issue an OFF command to the physical relay.
     */
    public function turnOff(Device $device): bool
    {
        Log::info("IoT Action: Sending OFF command to device {$device->name} ({$device->id})");

        try {
            $driver = $this->resolveDriver($device);
            $success = $driver->turnOff($device);

            if ($success) {
                Log::info("IoT Success: Device {$device->name} turned OFF.");
            } else {
                Log::error("IoT Failure: Driver failed to turn OFF device {$device->name}.");
            }

            return $success;
        } catch (\Throwable $e) {
            Log::critical("IoT Error: Exception during turnOff for device {$device->name}", [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
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
