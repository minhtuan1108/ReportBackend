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
        if ($user->isUser())
            return $this->indexUser($user);
        if ($user->isManager())
            return $this->indexManager();
        if ($user->isWorker())
            return $this->indexWorker($user);
        return new NotAllowed(null);
    }

    public function indexManager(){
        return ReportResource::collection(Report::orderBy('created_at', 'DESC')->paginate(30));
    }

    public function indexUser(User $user){
        return ReportResource::collection($user->reports()->orderBy('created_at', 'DESC')->paginate(30));
    }

    public function indexWorker(User $worker){
        return ReportResource::collection($worker->jobs()->orderBy('created_at', 'DESC')->paginate(30));
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
        foreach ($files as $file){
            $fileName = $file->storeAs($dir, "img$i".'_'.$report->id.'_'.sha1(time()).'.'.$file->extension());
            $paths[] = [
                'media_link' => asset($fileName),
                'local_file' => $fileName
            ];
            $i++;
        }
        $report->medias()->createMany($paths);
        return new ReportDetail(Report::with('medias')->find($report->id));
    }

    private function makeDir(){
        $now = Carbon::now();
        $dirNameYear = "photos/".$now->year;
        $dirNameMonth = $dirNameYear ."/". $now->month;
        // $dirExist = Storage::exists($dirNameYear) ? (Storage::exists($dirNameMonth) ? true : Storage::makeDirectory($dirNameMonth)) : (Storage::makeDirectory($dirNameMonth));
        if(Storage::exists($dirNameYear)){
            if(!Storage::exists($dirNameMonth))
                Storage::makeDirectory($dirNameMonth);
        }else{
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
        $report = Report::find($request->idReport);
        //Check target to update
        if($request->input('target') == 'ignore report'){
            return $this->ignoreReport($request, $report);
        }else{
            if($request->input('target') == 'create assignment'){
                return $this->updateToProcess($report);
            }
            
        }
        
        
    }

    //Ignore report
    private function ignoreReport(Request $request, Report $report){
        //Check report status
        if($report->status == ReportStatus::SENT){
            $this->authorize('update', $report);
            $report->feedback()->create([
                'note' => $request->input('note', 'Không có lý do'),
                'users_id' => $request->user()->id
            ]);
            $report->status = ReportStatus::IGNORE;
            $report->save();
            return [
                'status' => 'sussess',
                'message' => 'Từ chối báo cáo thành công!'
            ];
        }else return [
            'status' => 'fail',
            'message' => 'Không thể từ chối báo cáo với trạng thái '. $report->status
        ];
    } 

    //Update report status to process
    private function updateToProcess(Report $report){
        $report->status = ReportStatus::PROCESS;
        $report->save();
        return 'Cập nhật report thành process';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request ,string $id)
    {
        $report = Report::find($id);
        $this->authorize('delete', $report);
        echo($report);
        $this->hardDestroy($report);
    }

    public function hardDestroy(Report $report){
               
        //Delete feedback and medias with its local file if exist
        $feedbacks = $report->feedback()->get();  
        foreach($feedbacks as $feedback){
            $feedback->hardDelete();
        }

        //Delete report's medias with its local file if exist
        $report_medias = $report->medias();
        foreach($report_medias->get() as $media){
            $media->deleteLocalFile();
        }
        $report_medias->detach();
        
        //Delete assignment if exist
        $report->assignment()->delete();

        //Delete report
        $report->delete();
    } 
}
