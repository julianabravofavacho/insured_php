<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ApiResponse {

    public static function success($data) : JsonResponse {
        
        return response()->json(
            [
                'sucesso' => true,
                'dados' => $data,
                'message' => 'Sucesso'
            ]
            );
    }

    public static function error($message) : JsonResponse {
        
        return response()->json(
            [
                'status_code' => 500,
                'message' => $message
            ]
            );
    }

    public static function unathourized() : JsonResponse {
        
        return response()->json(
            [
                'status_code' => 401,
                'message' => 'Sem autorização'
            ]
            );
    }

}