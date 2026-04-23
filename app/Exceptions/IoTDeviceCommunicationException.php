<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class IoTDeviceCommunicationException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Hardware communication failed. The action was aborted to maintain consistency.',
        ], 503);
    }
}
