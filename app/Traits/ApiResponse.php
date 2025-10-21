<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function return($code, $message = '', $data = []): JsonResponse
    {
        return response()->json([
            'code'  => $code,
            'message' => $message,
            'data' => $data
        ], 200);
    }
}
