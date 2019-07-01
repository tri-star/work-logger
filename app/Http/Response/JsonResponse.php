<?php

namespace WorkLogger\Http\Response;

use Illuminate\Http\JsonResponse as BaseJsonResponse;

class JsonResponse extends BaseJsonResponse
{
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        $options |= JSON_UNESCAPED_UNICODE;
        parent::__construct($data, $status, $headers, $options);
    }
}
