<?php
namespace App\Http\Controllers\Api\v1;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Feedback\FeedbackDetail;
use App\Models\Feedback;
use App\Models\Report;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public ReportController $reportController;

    public function store(Request $request)
    {
        $report = Report::find($request->reports_id);
        $this->authorize('create', [Feedback::class ,$report->assignment]);
        
        $reportController = new ReportController();
        if ($report->status == ReportStatus::PROCESS) {
            
            $feedbackData = $request->merge(["users_id" => $request->user()->id, "target" => "create feedback"])->collect()->toArray();
            // echo("Hello ".implode(",", $feedbackData));
            $feedback = new Feedback($feedbackData);

            $files = $request->file('photo');
            echo(implode($files));
            $paths = [];
            $dir = $reportController->makeDir();
            $i = 1;
            foreach ($files as $file) {
                $fileName = $file->storeAs($dir, "img$i" . '_' . $report->id . '_' . sha1(time()) . '.' . $file->extension());
                $paths[] = [
                    'media_link' => asset($fileName),
                    'local_file' => $fileName
                ];
                $i++;
            }

            $feedback->save();
            $reportController->update($request);
            $feedback->medias()->createMany($paths);
            return new FeedbackDetail(Feedback::with('medias')->find($feedback->id));
        }
        return "Không thể tạo phản hồi cho báo cáo trong trạng thái " . $report->status;

    }

}