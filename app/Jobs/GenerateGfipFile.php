<?php

namespace App\Jobs;

use App\Models\Person;
use App\Services\Calculators\BcNumber;
use App\Services\Calculators\InssTable;
use App\Services\Gfip\Generator\GfipGenerator;
use App\Services\Gfip\Generator\GfipWriter;
use App\Services\Gfip\Types\Business;
use App\Services\Gfip\Types\Responsible;
use App\Services\GfipInfo;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Job that generates a GFIP report file using the available data. Only one
 * generation can occur per time, and the user should await for it to succeed
 * or fail and check if the file is available to download. The file will remain
 * available for a few days from completion, in case the generation take more
 * than a few minutes.
 * 
 * The following data will be considered during the generation:
 * 
 *   - Database table "persons" as is during the reading of chunks;
 *   - Json file "empresa_gfip.json";
 *   - Json file "responsavel_gfip.json";
 */
class GenerateGfipFile implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private object $currentInfoObject;
    private GfipInfo $service;
    
    private string $business_file = 'empresa_gfip.json';
    private string $responsible_file = 'responsavel_gfip.json';

    private object $input;
    private GfipGenerator $generator;
    private GfipWriter $writer;
    private Business $business;
    private Responsible $responsible;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->currentInfoObject = (object)[
            'state' => GfipInfo::STATE_QUEUED,
            'startedAt' => date(GfipInfo::DATE_FORMAT),
        ];
    }

    /**
     * Handle a job failure.
     * 
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $gfip = new GfipInfo();
        $data = $gfip->getLockData();
        $data->state = GfipInfo::STATE_COMPLETED;
        $data->completedAt = date(GfipInfo::DATE_FORMAT);
        $data->success = false;
        $data->message = $exception->getMessage();
        $gfip->setLockData($data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GfipInfo $gfip)
    {
        $this->service = $gfip;
        
        $this->updateLockFile([
            'state'=>GfipInfo::STATE_STARTED,
            'startedAt'=>date(GfipInfo::DATE_FORMAT)
        ]);

        $this->writer = new GfipWriter(function ($text) use ($gfip) {
            $gfip->appendOutput($text);
        });

        try {
            $this->generator = new GfipGenerator();
            $this->input = $this->service->getInputData();
            $this->business = $this->generator->getBusiness(Storage::get($this->business_file));
            $this->responsible = $this->generator->getResponsible(Storage::get($this->responsible_file));
            $type00 = $this->generator->getRowType00($this->business, $this->responsible, $this->input->year, $this->input->month, $this->input->codigo_recolhimento);
            $type10 = $this->generator->getRowType10($this->business, $this->input->codigo_centralizacao, $this->input->fpas);
            $type90 = $this->generator->getRowType90();
            $inss = $this->getInssTable();

            $gfip->eraseData();
            $this->writer->beginFile($type00);
            $this->writer->appendSection($type10);
            $this->handleDatabase($inss);
            $this->writer->endFile($type90);

            $this->updateLockFile([
                'state' => GfipInfo::STATE_COMPLETED,
                'completedAt' => date(GfipInfo::DATE_FORMAT),
                'success' => true,
                'message' => '',
            ]);
        } catch (Exception $e) {
            $this->updateLockFile([
                'state' => GfipInfo::STATE_COMPLETED,
                'completedAt' => date(GfipInfo::DATE_FORMAT),
                'success' => false,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function getInssTable() : InssTable
    {
        $contents = Storage::get('inss.json');
        $json = json_decode($contents);
        $aliquot = BcNumber::of($json->aliquot, 0)->divideBy10E(4);
        $ceil = BcNumber::of($json->ceil, 0)->divideBy10E(2);
        return new InssTable($aliquot, $ceil);
    }

    private function handleDatabase(InssTable $inss)
    {
        $ids = DB::table('persons')
            ->select(['id'])
            ->where('status', 1)
            ->orderBy('id')
            ->orderBy('pis')
            ->get()
            ->map(fn ($row) => $row->id);

        $this->updateLockFile([
            'state' => GfipInfo::STATE_RUNNING,
            'current'=>0,
            'total'=>$ids->count()
        ]);

        $current = 0;
        foreach($ids->chunk(10) as $chunk)
        {
            $persons = Person::withoutTrashed()->whereIn('id', $chunk)->get();
            $this->handlePersons($persons, $inss);
            $current += $chunk->count();
            $this->updateLockFile(['current' => $current]);
        }
        return;
    }

    private function handlePersons(Collection $persons, InssTable $inss)
    {
        foreach($persons as $person)
        {
            $this->handlePerson($person, $inss);
        }
    }
    
    private function handlePerson($person, InssTable $inss)
    {
        $type30 = $this->generator->getRowType30($this->business, $person, $inss);
        $this->writer->appendRecord($type30);
    }
    
    private function updateLockFile(array $fields = [])
    {
        foreach($fields as $key=>$value)
            $this->currentInfoObject->{$key} = $value;

        $this->service->setLockData($this->currentInfoObject);
    }
}