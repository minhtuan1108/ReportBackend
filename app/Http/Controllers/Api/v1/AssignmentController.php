<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assignment\AssignmentWorker;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller{
    

    //Store assignment
    public function store(Request $request){
        $this->authorize('create', Assignment::class);
        $assignmentData = $request->collect()->put("manager_id",$request->user()->id)->toArray();
        $assignment =  new Assignment($assignmentData);
        $assignment->save();
        $request->merge(['idReport' => $request->input('reports_id')]);
        return (new ReportController)->update($request);
        // return (new AssignmentWorker(Assignment::with(['worker', 'manager'])->find($assignment->id)));
    }
}