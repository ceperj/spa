<?php

namespace App\Jobs;

use App\Models\Person;
use App\Services\GfipInfo;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

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

    private $info;
    private GfipInfo $service;

    private const DATE_FORMAT = 'Y-m-d H:i:s';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->info = (object)[
            'started' => false,
            'completed' => false,
            'success' => false,
            'startedAt' => '',
            'completedAt' => '',
            'current' => 0,
            'total' => 0,
            'error' => '',
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GfipInfo $gfip)
    {
        $this->service = $gfip;
        $this->started = true;
        $this->startedAt = date(self::DATE_FORMAT);
        $this->updateLockFile([
            'started'=>true,
            'startedAt'=>date(self::DATE_FORMAT)
        ]);

        try {
            $gfip->eraseData();
            $this->handleDatabase();
            $this->updateLockFile([
                'started' => false,
                'completed' => true,
                'success' => true,
                'completedAt' => date(self::DATE_FORMAT)
            ]);
        } catch (Exception $e) {
            $this->updateLockFile([
                'started' => false,
                'completed' => true,
                'success' => false,
                'completedAt' => date(self::DATE_FORMAT),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function handleDatabase()
    {
        $ids = DB::table('persons')
            ->select(['id'])
            ->where('status', 1)
            ->orderBy('id')
            ->orderBy('battery_id')
            ->orderBy('project_id')
            ->get()
            ->map(fn ($row) => $row->id);

        $chunksOfIds = $ids->chunk(10);
        $this->updateLockFile(['total'=>$ids->count()]);

        $current = 0;
        foreach($chunksOfIds as $chunk)
        {
            $persons = Person::withoutTrashed()->whereIn('id', $chunk)->get();
            $this->handlePersons($persons);
            $current += $chunk->count();
            $this->updateLockFile(['current' => $current]);
        }
        return;
    }

    private function handlePersons($persons)
    {
        foreach($persons as $person)
        {
            $this->handlePerson($person);
        }
    }

    private function handlePerson($person)
    {
        $this->service->appendOutput("Processing line $person->id [project $person->project_id, battery $person->battery_id], status $person->status, created at $person->created_at, deleted at $person->deleted_at");
    }

    private function updateLockFile(array $fields = [])
    {
        foreach($fields as $key=>$value)
            $this->info->{$key} = $value;

        $this->service->setLockData($this->info);
    }
}
