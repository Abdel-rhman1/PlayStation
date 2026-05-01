<?php

namespace App\Services\Pricing;

use App\Models\Device;

class PlayerPricingResolverService
{
    /**
     * Resolve the hourly price for a device based on the player count.
     * 
     * @param Device $device
     * @param int|null $playerCount
     * @return float
     */
    public function getPrice(Device $device, ?int $playerCount = null): float
    {
        // 1. If player_count exists, try to find matching pricing
        if ($playerCount) {
            $pricing = $device->playerPricing()
                ->where('player_count', $playerCount)
                ->first();

            if ($pricing) {
                return (float) $pricing->price_per_hour;
            }
        }

        // 2. Fallback to device default price
        return (float) $device->hourly_rate;
    }
}
