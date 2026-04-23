<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartSessionRequest;
use App\Http\Requests\StopSessionRequest;
use App\Models\Device;
use App\Services\Sessions\StartSessionService;
use App\Services\Sessions\StopSessionService;
use Illuminate\Http\JsonResponse;

class DeviceSessionController extends Controller
{
    /**
     * Start device session.
     * POST /api/devices/{device}/start
     */
    public function start(StartSessionRequest $request, Device $device, StartSessionService $service): JsonResponse
    {
        $sessionDto = $service->execute($device, $request->user()?->getAuthIdentifier());

        return response()->json([
            'success' => true,
            'message' => 'Session started successfully.',
            'data' => $sessionDto->toArray(),
        ], 201);
    }

    /**
     * Stop device session.
     * POST /api/devices/{device}/stop
     */
    public function stop(StopSessionRequest $request, Device $device, StopSessionService $service): JsonResponse
    {
        $sessionDto = $service->execute($device, $request->user()?->getAuthIdentifier());

        return response()->json([
            'success' => true,
            'message' => 'Session stopped successfully.',
            'data' => $sessionDto->toArray(),
        ], 200);
    }
}
