<?php

namespace App\Domains\Inventory\Controllers\Web;

use App\Domains\Inventory\Requests\StoreDeviceRequest;
use App\Domains\Inventory\Requests\UpdateDeviceRequest;
use App\Domains\Inventory\Services\DeviceService;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DeviceController extends Controller
{
    public function __construct(
        protected DeviceService $deviceService
    ) {}

    public function index(): View
    {
        $devices = $this->deviceService->listDevices(100); // Increased for dashboard view
        $activeDevicesCount = Device::where('status', \App\Enums\DeviceStatus::IN_USE)->count();
        $branches = \App\Models\Branch::all();
        return view('devices.index', compact('devices', 'branches', 'activeDevicesCount'));
    }

    public function create(): View
    {
        $branches = \App\Models\Branch::all();
        return view('devices.create', compact('branches'));
    }

    public function store(StoreDeviceRequest $request): RedirectResponse
    {
        $this->deviceService->createDevice($request->validated());
        return redirect()->route('devices.index')->with('success', __('notifications.device_created'));
    }

    public function show(Device $device): View
    {
        $device->load(['branch', 'sessions.user']);
        return view('devices.show', compact('device'));
    }

    public function edit(Device $device): View
    {
        $branches = \App\Models\Branch::all();
        return view('devices.edit', compact('device', 'branches'));
    }

    public function update(UpdateDeviceRequest $request, Device $device): RedirectResponse
    {
        $this->deviceService->updateDevice($device, $request->validated());
        return redirect()->route('devices.index')->with('success', __('notifications.device_updated'));
    }

    public function destroy(Device $device): RedirectResponse
    {
        $this->deviceService->deleteDevice($device);
        return redirect()->route('devices.index')->with('success', __('notifications.device_deleted'));
    }
}
