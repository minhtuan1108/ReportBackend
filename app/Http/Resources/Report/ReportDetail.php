<?php

namespace App\Http\Resources\Report;

use App\Enums\ReportStatus;
use App\Http\Resources\Assignment\AssignmentWorker;
use App\Http\Resources\Assignment\AssignmentWorkerDoneBy;
use App\Http\Resources\Feedback\FeedbackDetail;
use App\Http\Resources\Feedback\FeedbackIgnore;
use App\Http\Resources\Media\MediaDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportDetail extends JsonResource
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
            'user' => [
                'name' => $this->user->name,
                'mssv' => $this->user->student_code,
            ],
            'images' => MediaDetail::collection($this->whenLoaded('medias')),
            'created_at' => $this->created_at,
            $this->mergeWhen($this->status == ReportStatus::COMPLETE, [
                'done_by' => new FeedbackDetail($this->feedback)
            ]),
            $this->mergeWhen($this->status == ReportStatus::PROCESS, [
                'done_by' => new AssignmentWorker($this->assignment)
            ]),
            $this->mergeWhen($this->status == ReportStatus::IGNORE, [
                'done_by' => new FeedbackIgnore($this->feedback)
            ]),
            $this->mergeWhen($this->status == ReportStatus::SENT,[
                'done_by' => [
                    'message' => 'Đang gửi',
                    'text' => 'Phản hồi của bạn đang được xử lý'
                ]
            ])
        ];
    }
}
