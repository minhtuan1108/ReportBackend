<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Resources\Error\NotAllowed;
use App\Http\Resources\Report\ReportDetail;
use App\Http\Resources\Report\ReportResource;
use App\Models\Media;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        foreach ($files as $file){
            $fileName = $file->storeAs('photos', "img$i".'_'.$report->id.'_'.sha1(time()).'.'.$file->extension());
            $paths[] = ['media_link' => asset($fileName)];
            $i++;
        }
        $report->medias()->createMany($paths);
        return new ReportDetail(Report::with('medias')->find($report->id));
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
    public function update(Request $request, string $id)
    {
        $report = Report::find($id);
        $this->authorize('update', $report);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $report = Report::find($id);
        $this->authorize('delete', $report);
    }
}
