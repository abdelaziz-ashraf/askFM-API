<?php

namespace App\Http\Responses;

class SuccessResponse {
    public static function send($message, $data = null, $statusCode = 200) {
        $response  = [
            'success' => true,
            'message' => $message,
        ];
        if(!is_null($data)) {
            $response['data'] = $data;
        }
        return response()->json($response , $statusCode);
    }
}
