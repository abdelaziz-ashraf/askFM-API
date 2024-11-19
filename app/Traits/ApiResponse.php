<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($data, $message = null, $code = 200, array $meta = [])
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
            'meta' => $meta
        ], $code);
    }

    protected function errorResponse($message = null, $code)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }
}
