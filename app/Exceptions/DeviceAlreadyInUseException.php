<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class DeviceAlreadyInUseException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Device is already in use.',
        ], 422);
    }
}
