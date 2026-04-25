<?php

namespace App\Jobs;

use App\Models\Device;
use App\Services\IoT\Contracts\DeviceDriverInterface;
use App\Services\IoT\Drivers\SonoffHttpDriver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ControlDeviceHardware implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Device $device,
        protected string $state
    ) {}

    /**
     * Execute the job.
     */
    public function handle(SonoffHttpDriver $driver): void
    {
        Log::info("Background IoT Action: Sending {$this->state} command to device {$this->device->name}");
        
        $success = ($this->state === 'on') 
            ? $driver->turnOn($this->device) 
            : $driver->turnOff($this->device);

        if (!$success) {
            Log::error("Background IoT Failure: Failed to set {$this->device->name} to {$this->state}");
        }
    }
}
