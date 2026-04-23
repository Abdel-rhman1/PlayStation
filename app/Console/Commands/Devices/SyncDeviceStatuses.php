<?php

namespace App\Console\Commands\Devices;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('devices:sync')]
#[Description('Synchronize device statuses with active sessions')]
class SyncDeviceStatuses extends Command
{
    public function handle()
    {
        $this->info('Starting device status synchronization...');
        $count = 0;

        \App\Models\Device::all()->each(function ($device) use (&$count) {
            $hasActiveSession = $device->activeSession()->exists();

            if ($hasActiveSession && $device->status !== \App\Enums\DeviceStatus::IN_USE) {
                $this->warn("Device {$device->name} has active session but status is {$device->status->value}. Setting to IN_USE.");
                $device->update(['status' => \App\Enums\DeviceStatus::IN_USE]);
                $count++;
            } elseif (!$hasActiveSession && $device->status === \App\Enums\DeviceStatus::IN_USE) {
                $this->warn("Device {$device->name} is marked IN_USE but has no active session. Resetting to OFF.");
                $device->update(['status' => \App\Enums\DeviceStatus::OFF]);
                $count++;
            }
        });

        if ($count > 0) {
            $this->info("Successfully synchronized {$count} devices.");
        } else {
            $this->info("All devices are already in sync.");
        }
    }
}
