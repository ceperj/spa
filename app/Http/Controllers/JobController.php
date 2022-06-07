<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\DropdownJobResource;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        return JobResource::collection(Job::all());
    }

    public function dropdownIndex()
    {
        return DropdownJobResource::collection(Job::onlyActive()->get());
    }

    public function show(Job $job)
    {
        return new JobResource($job);
    }

    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();
        $job = new Job();
        $job->fill($data);
        $job->user_id = Auth::user()->id;
        $job->save();
        return response('', 200);
    }

    public function update(UpdateJobRequest $request, Job $job)
    {
        $data = $request->validated();
        $job->fill($data);
        $job->save();
        return response('', 200);
    }
}
