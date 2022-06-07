<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InssController extends Controller
{
    protected $disk = 'local';
    protected $filename = 'inss.json';

    public function index()
    {
        $json = Storage::disk($this->disk)->get($this->filename);
        
        if ($json === null)
            $json = json_encode(['inss' => 0]);

        return ['data' => json_decode($json)];
    }

    public function store(Request $request)
    {
        if (! $request->has('aliquot'))
            abort(400);

        $contents = Storage::disk($this->disk)->get($this->filename);
        $json = json_decode($contents) ?? (object)[];
        
        $json->inss = (float)$request->get('aliquot');
        Storage::disk($this->disk)->put($this->filename, json_encode($json, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
        return response('', 200);
    }
}
