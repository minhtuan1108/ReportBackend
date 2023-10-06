<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Report\ReportResource;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        return null;
    }

    public function indexManager(){
        return ReportResource::collection(Report::orderBy('created_at', 'DESC')->paginate());
    }

    public function indexUser(User $user){
        return ReportResource::collection($user->reports()->orderBy('created_at', 'DESC')->paginate());
    }

    public function indexWorker(User $worker){
        return ReportResource::collection($worker->jobs()->orderBy('created_at', 'DESC')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
