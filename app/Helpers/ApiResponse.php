<?php

namespace App\Helpers;

use App\Enums\ResponseStatus;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ApiResponse
{
    public static function validation($messages = [], $data = [], $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json([
            'status'     => 'FAILED',
            'statusCode' => '400300',
            'data'       => $data,
            'message'    => $messages,
        ], $responseCode);
    }

    public static function success($data = [], $message = null, $responseCode = Response::HTTP_OK): JsonResponse
    {

        return response()->json([
            'status'     => 'SUCCESS',
            'statusCode' => '400200',
            'data'       => $data,
            'message'    => $message,
        ], $responseCode);
    }

    public static function error($message = null, $data = [], $responseCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status'     => 'FAILED',
            'statusCode' => '400500',
            'data'       => $data,
            'message'    => $message,
        ], $responseCode);
    }
}
