<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assignment\AssignmentWorker;
use App\Models\Assignment;
use App\Models\Report;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{


    //Store assignment
    public function store(Request $request)
    {
        $this->authorize('create', Assignment::class);
        $report = Report::find($request->reports_id);
        if ($report->status == ReportStatus::SENT) {
            $assignmentData = $request->collect()->put("manager_id", $request->user()->id)->toArray();
            $assignment = new Assignment($assignmentData);
            $assignment->save();
            (new ReportController)->update($request);
            return (new AssignmentWorker(Assignment::find($assignment->id)));
        }
        return "Không thể phân công nhiệm vụ vì báo cáo đang ở trạng thái ".$report->status;

    }
}