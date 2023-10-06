<?php

namespace App\Http\Resources\Media;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public $collects = Media::class;
    public function toArray(Request $request): array
    {
        return [
          'src' => $this->media_link,
        ];
    }
}
