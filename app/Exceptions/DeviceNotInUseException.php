<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class DeviceNotInUseException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Device is not currently in use.',
        ], 422);
    }
}
