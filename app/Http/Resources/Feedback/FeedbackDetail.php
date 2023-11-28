<?php

namespace App\Http\Resources\Feedback;

use App\Http\Resources\Media\MediaDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackDetail extends JsonResource
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
            'images' => MediaDetail::collection($this->whenLoaded('medias')),
            'time' => $this->created_at,
            'message' => 'Đã hoàn thành',
            'text' => 'Phản hồi đã được hoàn thành bởi '.$this->user->name.' vào lúc '.$this->created_at
        ];
    }
}
