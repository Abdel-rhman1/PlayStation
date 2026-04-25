<?php

namespace App\Services\IoT\Drivers;

use App\Models\Device;
use App\Services\IoT\Contracts\DeviceDriverInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SonoffHttpDriver implements DeviceDriverInterface
{
    protected const PORT = 8081;
    protected const RETRY_ATTEMPTS = 1;
    protected const RETRY_DELAY_MS = 200;
    protected const TIMEOUT = 2;

    /**
     * Send hardware ON command to device.
     */
    public function turnOn(Device $device): bool
    {
        return $this->sendCommand($device, 'on');
    }

    /**
     * Send hardware OFF command to device.
     */
    public function turnOff(Device $device): bool
    {
        return $this->sendCommand($device, 'off');
    }

    /**
     * Execute the raw HTTP command.
     */
    protected function sendCommand(Device $device, string $state): bool
    {
        $ip = $device->ip_address;

        if (!$ip) {
            Log::error("IoT Driver Error: No IP address configured for device {$device->name}");
            return false;
        }

        $url = "http://{$ip}:" . self::PORT . "/zeroconf/switch";

        try {
            $response = Http::timeout(self::TIMEOUT)
                ->retry(self::RETRY_ATTEMPTS, self::RETRY_DELAY_MS)
                ->post($url, [
                    'data' => [
                        'switch' => $state,
                    ],
                ]);

            if ($response->successful()) {
                Log::debug("IoT Driver Debug: Command {$state} successfully sent to {$ip}", [
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::error("IoT Driver Error: Command {$state} failed for {$ip}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;

        } catch (\Throwable $e) {
            Log::error("IoT Driver critical: Connection failure to {$ip}", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
