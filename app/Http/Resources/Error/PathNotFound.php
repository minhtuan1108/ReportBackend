<?php

namespace App\Http\Resources\Error;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PathNotFound extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'error' => 1,
            'message' => 'Lỗi! Sai method hoặc path. Hoặc bạn chưa đăng nhập, không có quyền truy cập'
        ];
    }
}
