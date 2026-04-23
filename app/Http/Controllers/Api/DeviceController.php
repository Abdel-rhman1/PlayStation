<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exceptions\SessionNotFoundException;
use App\Http\Resources\DeviceResource;
use App\Http\Resources\SessionResource;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeviceController extends Controller
{
    /**
     * List all devices with current status.
     * GET /api/devices
     */
    public function index(): AnonymousResourceCollection
    {
        return DeviceResource::collection(Device::all());
    }

    /**
     * Get active session for device.
     * GET /api/devices/{device}/active-session
     */
    public function activeSession(Device $device): SessionResource|\Illuminate\Http\JsonResponse
    {
        $session = $device->sessions()->where('status', 'active')->latest()->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'No active session found for this device.',
            ], 404);
        }

        return new SessionResource($session);
    }
}
