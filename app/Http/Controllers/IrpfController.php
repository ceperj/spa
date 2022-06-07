<?php

namespace App\Http\Controllers;

use App\Models\Irpf;
use App\Http\Requests\StoreIrpfRequest;
use App\Http\Requests\UpdateIrpfRequest;
use App\Http\Resources\IrpfResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IrpfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IrpfResource::collection(Irpf::all()->sortBy('min_cents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreIrpfRequest  $request
     * @return \Illuminate\Http\Response            Returns 200 or error.
     */
    public function store(StoreIrpfRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            Irpf::all()->each->delete();
            foreach ($data['irpf'] as $item) {
                $item['user_id'] = Auth::user()->id;
                Irpf::create($item);
            }
        });
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Irpf  $irpf
     * @return \Illuminate\Http\Response
     */
    public function show(Irpf $irpf)
    {
        return new IrpfResource($irpf);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateIrpfRequest  $request
     * @param  \App\Models\Irpf  $irpf
     * @return \Illuminate\Http\Response             Returns 200 or error.
     */
    public function update(UpdateIrpfRequest $request, Irpf $irpf)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;

        DB::transaction(function () use ($data, $irpf) {
            $irpf->delete();
            Irpf::create($data);
        });
    }
}
