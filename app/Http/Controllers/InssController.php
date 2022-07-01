<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInssRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InssController extends Controller
{
    protected $disk = 'local';
    protected $filename = 'inss.json';

    public function index()
    {
        $json = Storage::disk($this->disk)->get($this->filename);
        
        if (! $json)
            return ['data' => json_encode(['aliquot' => 0, 'ceil' => 0])];
        
        $obj = json_decode($json);
        return [
            'data' => (object)[
                'aliquot' => $obj->aliquot ?? 0,
                'ceil' => $obj->ceil ?? 0,
            ]
        ];
    }

    public function store(StoreInssRequest $request)
    {
        $data = $request->validated();

        $contents = Storage::disk($this->disk)->get($this->filename);
        $json = json_decode($contents) ?? (object)[];
        
        $json->aliquot = (int)$data['aliquot'];
        $json->ceil = (int)$data['ceil'];
        $json->comment = [
            '  Para formatar a aliquota, divida o valor de `aliquot` por 100 e     ',
            '  adicione o sufixo `%`. Zeros a direita no segmento dos decimais     ',
            '  podem ser removidos.                                                ',
            '  Para formatar o teto, divida o valor de `ceil` por 100 e adicione   ',
            '  o prefixo `R$`. O valor final deve conter exatamente duas casas     ',
            '  decimais.                                                           ',
        ];

        Storage::disk($this->disk)->put($this->filename, json_encode($json, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
        return response('', 200);
    }
}
