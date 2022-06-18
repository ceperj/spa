<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GfipInfo
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    static private $outputFile = 'gfip.txt';
    static private $lockFile = 'gfip.lock';
    static private $lockSeparator = " ||| ";
    static private $emptyInfo = [
        'started'=>false,
        'completed'=>false,
        'success'=>false,
        'startedAt'=>'',
        'startedAtRead'=>'',
        'updatedAt'=>'',
        'updatedAtRead'=>'',
        'completedAt'=>'',
        'completedAtRead'=>'',
        'current'=>0,
        'total'=>0,
        'error'=>'',
        'running'=>false,
        'progress'=>0,
        'expiresAt' => false,
    ];

    /**
     * Return information regarding to the file generation from the lock file.
     */
    public function info(int $expireInDays = 7)
    {
        $lock = $this->getLockData();

        if (! $lock)
            return (object)self::$emptyInfo;

        $availableTo = $this->getResultAvailability($lock->completedAt, $expireInDays);
        
        if ($lock->completed && ! $availableTo)
            return (object)self::$emptyInfo;

        $lock->expiresAt = $availableTo;

        return $lock;
    }

    /**
     * Is the application in a state where it can start a generation?
     */
    public function canStart()
    {
        $lock = $this->getLockData();

        if (! $lock)
            return true;

        return ! $lock->running;
    }

    /**
     * Can user access and download the generated file?
     * 
     * - The file exists;
     * - The generation is completed and succeeded;
     * - The generated file did not expire;
     */
    public function canDownload(int $expireInDays = 7)
    {
        $lock = $this->getLockData();

        if (! $lock->completed)
            return false;

        return !! $this->getResultAvailability($lock->completedAt, $expireInDays);
    }

    /**
     * Delete the previous generated output file.
     */
    public function eraseData()
    {
        Storage::delete(self::$outputFile);
    }

    /**
     * Append a new line to the output file.
     */
    public function appendOutput(string $line)
    {
        return Storage::append(self::$outputFile, $line);
    }

    /**
     * Return a downloadable output file.
     */
    public function downloadOutput()
    {
        return Storage::download(self::$outputFile);
    }

    /**
     * Replace the lock file by this data.
     */
    public function setLockData(object $info)
    {
        $contents = implode(self::$lockSeparator, [
            $info->started,
            $info->completed,
            $info->success,
            $info->startedAt,
            date(self::DATE_FORMAT),
            $info->completedAt,
            $info->current,
            $info->total,
            $info->error,
        ]);
        return Storage::put(self::$lockFile, $contents);
    }

    private function getLockData()
    {
        if (! Storage::exists(self::$lockFile))
            return null;

        $contents = Storage::get(self::$lockFile);
        $info = explode(self::$lockSeparator, $contents, 9);
        $result = (object)[
            'started' => !! $info[0],
            'completed' => !! $info[1],
            'success' => !! $info[2],
            'startedAt' => $info[3],
            'updatedAt' => $info[4],
            'completedAt' => $info[5],
            'current' => (int)$info[6],
            'total' => (int)$info[7],
            'error' => $info[8],
        ];
        $result->running = $result->started && ! $result->completed;
        $result->progress = ! $result->total ? null : (int)((float)$result->current / (float)$result->total * 100);
        $result->startedAtRead = ! $result->startedAt ? '' : Carbon::createFromFormat(self::DATE_FORMAT, $result->startedAt)->diffForHumans();
        $result->completedAtRead = ! $result->completedAt ? '' : Carbon::createFromFormat(self::DATE_FORMAT, $result->completedAt)->diffForHumans();
        $result->updatedAtRead = ! $result->updatedAt ? '' : Carbon::createFromFormat(self::DATE_FORMAT, $result->updatedAt)->diffForHumans();
        return $result;
    }

    /**
     * Returns false if the result does not exist or is expired, or return a
     * string (that always evaluates to true) with a human display for "fromNow"
     * to expiration date. The human display follows the application "lang".
     */
    private function getResultAvailability(string $completedAt, int $expireInDays)
    {
        if (! $completedAt || ! Storage::exists(self::$outputFile))
            return false;

        $now = Carbon::now();
        $completed = Carbon::createFromFormat(self::DATE_FORMAT, $completedAt);
        $expiration = $completed->clone()->addDays($expireInDays);

        if ($now->greaterThan($expiration))
            return false;
        
        return $expiration->fromNow();
    }
}
