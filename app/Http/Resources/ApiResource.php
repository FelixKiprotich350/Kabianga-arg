<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    protected $message;
    protected $errors;
    protected $meta;

    public function __construct($resource, $message = 'Resource retrieved successfully', $errors = null, $meta = [])
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->errors = $errors;
        $this->meta = $meta;
    }

    public function toArray($request)
    {
        return [
            'status' => 'success',
            'code' => 200,
            'message' => $this->message,
            'data' => $this->resource,
            'errors' => $this->errors,
            'meta' => array_merge([
                'version' => '1.0'
            ], $this->meta)
        ];
    }
}