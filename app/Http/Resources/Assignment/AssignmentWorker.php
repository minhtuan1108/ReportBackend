<?php

namespace App\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentWorker extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->worker->name,
            'time' => $this->created_at,
            'message' => 'Đang thực hiện',
            'text' => 'Phản hồi của bạn đang được thực hiện bởi '.$this->worker->name.' vào lúc '. $this->created_at,
            $this->mergeWhen($request->user() != null && $request->user()->isWorker(), [
                'manager_note' => $this->note,
                'manager_name' => $this->manager->name,
            ])
        ];
    }
}
