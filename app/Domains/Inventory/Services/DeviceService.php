<?php
namespace App\Domains\Inventory\Services;

use App\Models\Device;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DeviceService
{
    /**
     * List all devices with pagination.
     */
    public function listDevices(int $perPage = 15): LengthAwarePaginator
    {
        return Device::with(['branch', 'activeSession', 'playerPricing'])->paginate($perPage);
    }

    /**
     * Create a new device.
     */
    public function createDevice(array $data): Device
    {
        return DB::transaction(function() use ($data) {
            $playerPricing = $data['player_pricing'] ?? [];
            
            // Derive hourly_rate from 2P pricing if not explicitly provided
            if ((!isset($data['hourly_rate']) || $data['hourly_rate'] === null) && isset($playerPricing[2])) {
                $data['hourly_rate'] = $playerPricing[2];
            }
            $data['fixed_rate'] = $data['fixed_rate'] ?? 0;
            
            unset($data['player_pricing']);
            
            $device = Device::create($data);
            
            $this->syncPlayerPricing($device, $playerPricing);
            
            return $device;
        });
    }

    /**
     * Update an existing device.
     */
    public function updateDevice(Device $device, array $data): Device
    {
        return DB::transaction(function() use ($device, $data) {
            $playerPricing = $data['player_pricing'] ?? [];
            unset($data['player_pricing']);
            
            $device->update($data);
            
            $this->syncPlayerPricing($device, $playerPricing);
            
            return $device;
        });
    }

    /**
     * Synchronize player-specific pricing for a device.
     */
    protected function syncPlayerPricing(Device $device, array $pricing): void
    {
        foreach ($pricing as $count => $rate) {
            if ($rate === null || $rate === '') {
                $device->playerPricing()->where('player_count', $count)->delete();
                continue;
            }

            $device->playerPricing()->updateOrCreate(
                ['player_count' => (int) $count],
                ['price_per_hour' => $rate, 'tenant_id' => $device->tenant_id]
            );
        }
    }

    /**
     * Delete a device.
     */
    public function deleteDevice(Device $device): void
    {
        $device->delete();
    }
}
