<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use App\Constants\HttpResponseCode as ResponseCode;

trait HttpResponse
{
    public function success($data, string $message = null, int $code = ResponseCode::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error($data, string $message = null, int $code = ResponseCode::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
