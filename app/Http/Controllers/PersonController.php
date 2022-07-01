<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Person;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\ListablePersonResource;
use App\Http\Resources\PersonResource;
use App\Http\Transforms\PersonRequestTransform;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ListablePersonResource::collection(Person::withTrashed()->paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonRequest $request, PersonRequestTransform $transform)
    {
        $data = $request->validated();
        $data = $transform->transform($data);
        $model = new Person();
        $model->fill($data);
        $model->status = Constants::STATUS_ACTIVE;
        $model->user_id = Auth::id();
        $model->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        return new PersonResource($person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePersonRequest  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePersonRequest $request, Person $person, PersonRequestTransform $transform)
    {
        $data = $request->validated();
        $data = $transform->transform($data);
        $person->fill($data);
        $person->status = (int)$data['status'];
        $person->save();

        if ((int)$data['status'] === Constants::STATUS_INACTIVE)
            $person->delete();
    }
}