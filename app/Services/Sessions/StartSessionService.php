<?php

namespace App\Services\Sessions;

use App\DTOs\SessionResponseDTO;
use App\Exceptions\DeviceAlreadyInUseException;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Session;
use App\Services\IoT\DeviceControlService;
use Illuminate\Support\Facades\DB;

class StartSessionService
{
    public function __construct(protected DeviceControlService $deviceControlService)
    {
    }

    /**
     * @param Device $device
     * @param string|null $userId Options to log which user performed this action
     * @return SessionResponseDTO
     * @throws DeviceAlreadyInUseException
     */
    public function execute(Device $device, ?string $userId = null): SessionResponseDTO
    {
        if ($device->status === 'in_use') {
            throw new DeviceAlreadyInUseException();
        }

        $dto = DB::transaction(function () use ($device, $userId) {
            // Fire IoT physical switch ON request
            $success = $this->deviceControlService->turnOn($device);

            if (!$success) {
                // Fails the transaction and prevents the session from being formally created
                throw new \App\Exceptions\IoTDeviceCommunicationException('Hardware failed to turn ON.');
            }
            // Mark device as ON (in_use)
            $device->update(['status' => 'in_use']);

            // Create new session
            $session = Session::create([
                'device_id' => $device->id,
                'started_at' => now(),
                'status' => 'active',
                'pricing_type' => 'per_hour',
            ]);

            // Log action
            DeviceLog::create([
                'device_id' => $device->id,
                'action' => 'ON',
                'user_id' => $userId,
            ]);

            return SessionResponseDTO::fromModel($session);
        });

        event(new \App\Events\DeviceTurnedOn($device));

        return $dto;
    }
}
