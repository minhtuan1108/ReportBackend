<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\Media\MediaDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location_text,
            'status' => $this->status,
            'user' => $this->user->name,
            'avatar' => $this->medias[0],
            'created_at' => $this->created_at
        ];
    }
}
