<?php

namespace App\Domains\Inventory\Controllers\Api;

use App\Domains\Inventory\Requests\StoreDeviceRequest;
use App\Domains\Inventory\Requests\UpdateDeviceRequest;
use App\Domains\Inventory\Resources\DeviceResource;
use App\Domains\Inventory\Services\DeviceService;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected DeviceService $deviceService
    ) {}

    public function index(): JsonResponse
    {
        $devices = $this->deviceService->listDevices();
        return $this->success(DeviceResource::collection($devices));
    }

    public function store(StoreDeviceRequest $request): JsonResponse
    {
        $device = $this->deviceService->createDevice($request->validated());
        return $this->success(new DeviceResource($device), 'Device created successfully.', 201);
    }

    public function show(Device $device): JsonResponse
    {
        return $this->success(new DeviceResource($device->load('branch')));
    }

    public function update(UpdateDeviceRequest $request, Device $device): JsonResponse
    {
        $updatedDevice = $this->deviceService->updateDevice($device, $request->validated());
        return $this->success(new DeviceResource($updatedDevice), 'Device updated successfully.');
    }

    public function destroy(Device $device): JsonResponse
    {
        $this->deviceService->deleteDevice($device);
        return $this->success(null, 'Device deleted successfully.');
    }
}
