<?php

namespace App\Domains\Inventory\Services;

use App\Models\Device;
use Illuminate\Pagination\LengthAwarePaginator;

class DeviceService
{
    /**
     * List all devices with pagination.
     */
    public function listDevices(int $perPage = 15): LengthAwarePaginator
    {
        return Device::with(['branch', 'activeSession'])->paginate($perPage);
    }

    /**
     * Create a new device.
     */
    public function createDevice(array $data): Device
    {
        return Device::create($data);
    }

    /**
     * Update an existing device.
     */
    public function updateDevice(Device $device, array $data): Device
    {
        $device->update($data);
        return $device;
    }

    /**
     * Delete a device.
     */
    public function deleteDevice(Device $device): void
    {
        $device->delete();
    }
}
