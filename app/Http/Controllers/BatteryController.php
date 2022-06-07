<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatteryRequest;
use App\Http\Resources\BatteryResource;
use App\Http\Resources\DropdownBatteryResource;
use App\Models\Battery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatteryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BatteryResource::collection(Battery::all());
    }

    /**
     * Display a listing of the active resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dropdownIndex()
    {
        return DropdownBatteryResource::collection(Battery::onlyActive()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Battery  $battery
     * @return \Illuminate\Http\Response
     */
    public function show(Battery $battery)
    {
        return new BatteryResource($battery);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BatteryRequest $request)
    {
        $input = $request->validated();
        $batteries = [...Battery::all()];
        $this->createMissingModels($batteries, $input['battery']);
        $this->processCurrentBatteries($batteries, $input['battery']);
    }

    /**
     * Create missing models in $batteries array, according to the count
     * of input data in $input array.
     * 
     * @param array $batteries Reference to array of Battery models. This array may receive new members.
     * @param array $input Array with batteries' input.
     */
    private function createMissingModels(&$batteries, $input)
    {
        $count = count($batteries);
        $expected = count($input);
        for ($i = $count; $i < $expected; $i++)
        {
            array_push($batteries, new Battery());
        }
    }

    /**
     * Update each model with the input values.
     * 
     * @param array $batteries Array of batteries whose count must be equals or greater than $input count.
     * @param array $input Array with batteries' input to update the models.
     */
    private function processCurrentBatteries($batteries, $input)
    {
        $count = count($input);
        for ($i = 0; $i < $count; $i++)
        {
            $batteries[$i]['date'] = (int)$input[$i]['date'];
            $batteries[$i]['status'] = (int)$input[$i]['status'];
        }

        DB::transaction(function () use ($batteries){
            foreach($batteries as $battery)
            {
                $battery->save();
            }
        });
    }
}
