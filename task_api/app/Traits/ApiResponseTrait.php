<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ApiResponseTrait
{
    protected function successResponse($data, $message = null, $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = null, $code= 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'fail',
            'message' => $message,
            'data' => null
        ], $code);
    }

    protected function validationErrorResponse($error, $message = null, $code = 422): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'fail',
            'message' => $message,
            'error' => $error
        ], $code);
    }
}
