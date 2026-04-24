<?php

namespace App\Domains\Sessions\Services;

use App\Domains\Sessions\Exceptions\DeviceNotActiveException;
use App\Domains\Sessions\Exceptions\DeviceNotAvailableException;
use App\Enums\DeviceStatus;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Session;
use App\Models\Receipt;
use App\Services\Sessions\SessionBillingService;
use App\Services\IoT\DeviceControlService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class SessionService
{
    public function __construct(
        protected DeviceControlService $deviceControlService,
        protected SessionBillingService $billingService
    ) {}

    /**
     * Start a new session for a device.
     */
    public function startSession(Device $device, int $userId): Session
    {
        if ($device->status === DeviceStatus::IN_USE) {
            $hasActiveSession = $device->activeSession()->exists();

            if ($hasActiveSession) {
                throw new DeviceNotAvailableException("Device {$device->name} is already in use.");
            }

            // Self-healing: Device is marked IN_USE but has no active session record
            \Illuminate\Support\Facades\Log::warning("Syncing state: Device {$device->name} was marked IN_USE without an active session record.");
        }

        if ($device->status !== DeviceStatus::OFF && $device->status !== DeviceStatus::ON && $device->status !== DeviceStatus::IN_USE) {
            throw new DeviceNotAvailableException("Device {$device->name} is in an unavailable state: {$device->status->value}.");
        }

        return DB::transaction(function () use ($device, $userId) {
            // Optional: IoT Hardware call
            $this->deviceControlService->turnOn($device);

            $device->update(['status' => DeviceStatus::IN_USE]);

            $session = Session::create([
                'tenant_id' => $device->tenant_id,
                'device_id' => $device->id,
                'user_id' => $userId,
                'started_at' => now(),
                'status' => 'active',
                'pricing_type' => 'per_hour',
            ]);

            DeviceLog::create([
                'device_id' => $device->id,
                'action' => 'START_SESSION',
                'user_id' => $userId,
            ]);

            event(new \App\Events\DeviceTurnedOn($device));

            return $session;
        });
    }

    /**
     * Stop an active session for a device.
     */
    public function stopSession(Device $device, int $userId): Session
    {
        if ($device->status !== DeviceStatus::IN_USE) {
            throw new DeviceNotActiveException();
        }

        return DB::transaction(function () use ($device, $userId) {
            $session = Session::where('device_id', $device->id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$session) {
                throw new BadRequestException('No active session found for this device.');
            }

            // Optional: IoT Hardware call
            $this->deviceControlService->turnOff($device);

            $endedAt = now();
            $session->ended_at = $endedAt; // Temp set for calc

            $billing = $this->billingService->calculateTotal($session);
            
            $session->update([
                'ended_at'    => $endedAt,
                'cost'        => $billing['device_price'],
                'total_price' => $billing['grand_total'],
                'status'      => 'completed',
            ]);

            // Store Receipt Snapshot
            Receipt::create([
                'session_id'   => $session->id,
                'device_price' => $billing['device_price'],
                'orders_total' => $billing['orders_total'],
                'grand_total'  => $billing['grand_total'],
                'snapshot'     => $this->billingService->generateReceiptData($session),
            ]);

            $device->update(['status' => DeviceStatus::OFF]);

            DeviceLog::create([
                'device_id' => $device->id,
                'action' => 'STOP_SESSION',
                'user_id' => $userId,
            ]);

            event(new \App\Events\DeviceTurnedOff($device));

            return $session;
        });
    }
}
