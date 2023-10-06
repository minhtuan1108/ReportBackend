<?php

namespace App\Http\Resources\Feedback;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackIgnore extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user->name,
            'note' => $this->note,
            'time' => $this->created_at,
            'message' => 'Bị bỏ qua bởi quản trị viên',
            'text' => 'Quản trị viên đã bỏ qua phản hồi này với lý do: '. $this->note
        ];
    }
}
