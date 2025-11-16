<?php

namespace App\Traits;

use App\Http\Resources\ApiResource;

trait ApiResponse
{
    protected function successResponse($data, $message = 'Resource retrieved successfully', $meta = [], $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'errors' => null,
            'meta' => array_merge([
                'version' => '1.0'
            ], $meta)
        ], $code);
    }

    protected function errorResponse($message = 'An error occurred', $errors = null, $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
            'meta' => [
                'version' => '1.0'
            ]
        ], $code);
    }

    protected function paginatedResponse($data, $message = 'Resources retrieved successfully')
    {
        $meta = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl()
            ]
        ];

        return $this->successResponse($data->items(), $message, $meta);
    }
}