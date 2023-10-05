<?php

namespace App\Http\Traits;

trait ApiResponse {
    protected function responseJSON($statusJson, $statusCode = 200)
    {
        return response()->json($statusJson, $statusCode);
    }
}
