<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\DropdownProjectResource;
use App\Http\Resources\ListableProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Auth;

/**
 * API controller
 */
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ListableProjectResource::collection(Project::paginate(15));
    }

    public function dropdownIndex(){
        return DropdownProjectResource::collection(Project::onlyActive()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $data = array_filter($data, static fn($value) => $value !== null);
        $project = new Project();
        $project->fill($data);
        $project->status = Constants::STATUS_ACTIVE;
        $project->user_id = Auth::id();
        $project->save();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $data = array_filter($data, static fn($value) => $value !== null);
        $project->fill($data);
        $project->status = (int)$data['status'];
        $project->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Project $project)
    {
        $project->status = Constants::STATUS_INACTIVE;
        $project->save();
    }
}
