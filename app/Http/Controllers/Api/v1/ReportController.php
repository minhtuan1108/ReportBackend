<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Resources\Error\NotAllowed;
use App\Http\Resources\Report\ReportDetail;
use App\Http\Resources\Report\ReportResource;
use App\Models\Feedback;
use App\Models\Media;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\Type\TrueType;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $title = $request->input('text', '');
        $fromDate = $request->input('from', '');
        $toDate = $request->input('to', '');
        $status = $request->input('status', '');

        if($fromDate == null){
            $fromDate = date('Y-m-d', 0);
        }

        if($toDate == null){
            $toDate = date('Y-m-d');
        }

        if ($user->isUser())
            return $this->indexUser($user, $title, $fromDate, $toDate, $status);
        if ($user->isManager())
            return $this->indexManager($title, $fromDate, $toDate, $status);
        if ($user->isWorker())
            return $this->indexWorker($user, $title, $fromDate, $toDate, $status);
        return new NotAllowed(null);
    }

    // Search bằng tên, nội dung, label, trạng thái, date
    public function indexManager($title, $fromDate, $toDate, $status)
    {
        if($status == '' || $status == 'all'){
            return ReportResource::collection(Report::with('medias')
                                                    ->where(function ($query) use ($title) {
                                                        $query->where('title', 'like', '%' . $title . '%')
                                                            ->orWhere('description', 'like', '%' . $title . '%');
                                                    })
                                                    ->where('created_at', '>=', $fromDate)
                                                    ->where('created_at', '<=', $toDate)
                                                    ->orderBy('created_at', 'DESC')->get());
        }
        return ReportResource::collection(Report::with('medias')
                                                ->where(function ($query) use ($title) {
                                                    $query->where('title', 'like', '%' . $title . '%')
                                                        ->orWhere('description', 'like', '%' . $title . '%');
                                                })
                                                ->where('created_at', '>=', $fromDate)
                                                ->where('created_at', '<=', $toDate)
                                                ->where('status', $status)
                                                ->orderBy('created_at', 'DESC')->get());
    }

    public function indexUser(User $user, $title, $fromDate, $toDate, $status)
    {
        if($status == '' || $status == 'all')
            return ReportResource::collection($user->reports()->with('medias')
                                                ->where(function ($query) use ($title) {
                                                    $query->where('title', 'like', '%' . $title . '%')
                                                        ->orWhere('description', 'like', '%' . $title . '%');
                                                })
                                                ->where('created_at', '>=', $fromDate)
                                                ->where('created_at', '<=', $toDate)
                                                ->orderBy('created_at', 'DESC')->get());
        return ReportResource::collection($user->reports()->with('medias')
                                            ->where(function ($query) use ($title) {
                                                $query->where('title', 'like', '%' . $title . '%')
                                                    ->orWhere('description', 'like', '%' . $title . '%');
                                            })
                                            ->where('created_at', '>=', $fromDate)
                                            ->where('created_at', '<=', $toDate)
                                            ->where('status', $status)
                                            ->orderBy('created_at', 'DESC')->get());
    }

    public function indexWorker(User $worker, $title, $fromDate, $toDate, $status)
    {
        if($status == '' || $status == 'all')
            return ReportResource::collection($worker->reportWorker()->with('medias')
                                                ->where(function ($query) use ($title) {
                                                    $query->where('reports.title', 'like', '%' . $title . '%')
                                                        ->orWhere('reports.description', 'like', '%' . $title . '%');
                                                })
                                                ->where('reports.created_at', '>=', $fromDate)
                                                ->where('reports.created_at', '<=', $toDate)
                                                ->orderBy('reports.created_at', 'DESC')->get());
        return ReportResource::collection($worker->reportWorker()->with('medias')
                                            ->where(function ($query) use ($title) {
                                                $query->where('reports.title', 'like', '%' . $title . '%')
                                                    ->orWhere('reports.description', 'like', '%' . $title . '%');
                                            })
                                            ->where('reports.created_at', '>=', $fromDate)
                                            ->where('reports.created_at', '<=', $toDate)
                                            ->where('reports.status', $status)
                                            ->orderBy('reports.created_at', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $this->authorize('create', Report::class);
        $validated = $request->validated();
        $report = new Report($validated);
        $request->user()->reports()->save($report);

        $files = $request->file()['photo'];
        $paths = [];
        $i = 1;
        $dir = $this->makeDir();
        foreach ($files as $file) {
            $fileName = $file->storeAs($dir, "img$i" . '_' . $report->id . '_' . sha1(time()) . '.' . $file->extension());
            $paths[] = [
                'media_link' => asset($fileName),
                'local_file' => $fileName
            ];
            $i++;
        }
        $report->medias()->createMany($paths);
        return new ReportDetail(Report::with('medias')->find($report->id));
    }

    public function makeDir()
    {
        $now = Carbon::now();
        $dirNameYear = "photos/" . $now->year;
        $dirNameMonth = $dirNameYear . "/" . $now->month;
        // $dirExist = Storage::exists($dirNameYear) ? (Storage::exists($dirNameMonth) ? true : Storage::makeDirectory($dirNameMonth)) : (Storage::makeDirectory($dirNameMonth));
        if (Storage::exists($dirNameYear)) {
            if (!Storage::exists($dirNameMonth))
                Storage::makeDirectory($dirNameMonth);
        } else {
            Storage::makeDirectory($dirNameMonth);
        }
        // echo $dirNameMonth;
        return $dirNameMonth;
    }
    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $report = Report::find($id);
        $this->authorize('view', $report);
        return new ReportDetail(Report::with(['feedback.medias', 'feedback.user', 'medias', 'assignment.worker'])->find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $report = Report::find($request->reports_id);
        //Check target to update
        if ($request->input('target') == 'create assignment') {
            $report->status = ReportStatus::PROCESS;
            $report->save();
            return 'Cập nhật report thành process';
        } elseif ($request->input('target') == 'create feedback') {
            $report->status = ReportStatus::COMPLETE;
            $report->save();
            return 'Cập nhật report thành complete';
        }


    }

    //Ignore report
    public function ignoreReport(Request $request)
    {
        $report = Report::find($request->reports_id);
        //Check report status
        if ($report->status == ReportStatus::SENT) {
            $this->authorize('update', $report);
            $report->feedback()->create([
                'note' => $request->input('note', $request->input('note')),
                'users_id' => $request->user()->id
            ]);
            $report->status = ReportStatus::IGNORE;
            $report->save();
            return [
                'status' => 'success',
                'message' => 'Từ chối báo cáo thành công!'
            ];
        } else
            return [
                'status' => 'fail',
                'message' => 'Không thể từ chối báo cáo với trạng thái ' . $report->status
            ];
    }

    //Update report status to process
    private function updateToProcess(Report $report)
    {
        $report->status = ReportStatus::PROCESS;
        $report->save();
        return 'Cập nhật report thành process';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $report = Report::find($id);
        $this->authorize('delete', $report);
        $this->hardDestroy($report);
        return [
            "status" => "success",
            "message" => "Xóa thành công"
        ];
    }

    public function hardDestroy(Report $report)
    {

        //Delete feedback and medias with its local file if exist
        $feedbacks = $report->feedback()->get();
        foreach ($feedbacks as $feedback) {
            $feedback->hardDelete();
        }

        //Delete report's medias with its local file if exist
        $report_medias = $report->medias();
        foreach ($report_medias->get() as $media) {
            $media->deleteLocalFile();
        }
        $report_medias->detach();

        //Delete assignment if exist
        $report->assignment()->delete();

        //Delete report
        $report->delete();
    }
}
