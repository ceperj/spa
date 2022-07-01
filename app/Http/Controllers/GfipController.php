<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateGfipFile;
use App\Services\GfipInfo;

class GfipController extends Controller
{
    public function index(GfipInfo $gfip)
    {
        return $gfip->info();
    }

    public function start(GfipInfo $gfip)
    {
        if (! $gfip->canStart())
            return response('', 400);

        $gfip->resetLockData();
        GenerateGfipFile::dispatch();
        return response('', 200);
    }

    public function download(GfipInfo $gfip)
    {
        if (! $gfip->canDownload())
            return response('O arquivo não foi gerado ou o link expirou.', 404);

        return $gfip->downloadOutput();
    }
}
