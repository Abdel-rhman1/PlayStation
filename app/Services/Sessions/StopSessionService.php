<?php

namespace App\Services\Sessions;

use App\DTOs\SessionResponseDTO;
use App\Exceptions\DeviceNotInUseException;
use App\Exceptions\SessionNotFoundException;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Session;
use App\Services\IoT\DeviceControlService;
use Illuminate\Support\Facades\DB;

class StopSessionService
{
    public function __construct(protected DeviceControlService $deviceControlService)
    {
    }

    /**
     * @param Device $device
     * @param string|null $userId Options to log which user performed this action
     * @return SessionResponseDTO
     * @throws DeviceNotInUseException
     * @throws SessionNotFoundException
     */
    public function execute(Device $device, ?string $userId = null): SessionResponseDTO
    {
        if ($device->status !== 'in_use') {
            throw new DeviceNotInUseException();
        }

        $dto = DB::transaction(function () use ($device, $userId) {
            // Fire IoT physical switch OFF request
            $success = $this->deviceControlService->turnOff($device);

            if (!$success) {
                // Fails the transaction
                throw new \App\Exceptions\IoTDeviceCommunicationException('Hardware failed to turn OFF.');
            }

            $session = Session::where('device_id', $device->id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if (! $session) {
                // Failsafe in case status is out of sync
                throw new SessionNotFoundException();
            }

            $endedAt = now();
            
            // Calculate duration in hours
            $durationInMinutes = $session->started_at->diffInMinutes($endedAt);
            $durationInHours = max($durationInMinutes / 60, 0); // avoid negative just in case

            // Calculate cost using device's hourly rate
            $hourlyRate = $device->hourly_rate ?? 0;
            $cost = $durationInHours * $hourlyRate;

            // Optional: apply minimum cost or fixed rate if 'pricing_type' logic gets complex later

            // Update Session
            $session->update([
                'ended_at' => $endedAt,
                'cost' => $cost,
                'status' => 'completed'
            ]);

            // Mark device as OFF (available)
            $device->update(['status' => 'available']);

            // Log action
            DeviceLog::create([
                'device_id' => $device->id,
                'action' => 'OFF',
                'user_id' => $userId,
            ]);

            return SessionResponseDTO::fromModel($session);
        });

        event(new \App\Events\DeviceTurnedOff($device));

        return $dto;
    }
}
