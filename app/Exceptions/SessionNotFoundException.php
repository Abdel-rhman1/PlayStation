<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class SessionNotFoundException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'No active session found for this device.',
        ], 404);
    }
}
