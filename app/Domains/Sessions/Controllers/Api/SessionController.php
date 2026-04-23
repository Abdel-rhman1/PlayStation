<?php

namespace App\Domains\Sessions\Controllers\Api;

use App\Domains\Sessions\Resources\SessionResource;
use App\Domains\Sessions\Services\SessionService;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected SessionService $sessionService
    ) {}

    /**
     * Start a session for a device.
     */
    public function start(Device $device, Request $request): JsonResponse
    {
        $session = $this->sessionService->startSession($device, $request->user()->id);

        return $this->success(new SessionResource($session), 'Session started.', 201);
    }

    /**
     * Stop a session for a device.
     */
    public function stop(Device $device, Request $request): JsonResponse
    {
        $session = $this->sessionService->stopSession($device, $request->user()->id);

        return $this->success(new SessionResource($session), 'Session stopped.');
    }
}
