<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GfipInfo
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';
    public const STATE_NOT_STARTED = 'NOTSTARTED';
    public const STATE_QUEUED = 'QUEUED';
    public const STATE_STARTED = 'STARTED';
    public const STATE_RUNNING = 'RUNNING';
    public const STATE_COMPLETED = 'COMPLETED';

    static private $outputFile = 'gfip-result.txt';
    static private $lockFile = 'gfip-lock.json';
    static private $inputFile = 'gfip-input.json';
    static private $emptyInfo = ['state' => self::STATE_NOT_STARTED];

    public function info($expireInDays = 7)
    {
        $json = $this->getLockData();

        if ($json->state === self::STATE_COMPLETED)
        {
            $availability = $this->getResultAvailability($json->completedAt, $expireInDays);
            if ($availability)
                $json->expiresAt = $availability;
            else
                $json = self::$emptyInfo;
        }

        return $json;
    }

    public function canStart(){
        if (! Storage::exists(self::$lockFile))
            return true;
        
        $json = $this->getLockData();
        return ($json->state === self::STATE_COMPLETED)
            || ($json->state === self::STATE_NOT_STARTED);
    }

    public function canDownload($expireInDays = 7){
        if (! Storage::exists(self::$lockFile))
            return false;
        
        if (! Storage::exists(self::$outputFile))
            return false;
        
        $json = $this->getLockData();
        if ($json->state !== self::STATE_COMPLETED)
            return false;
        
        return $this->getResultAvailability($json->completedAt, $expireInDays);
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

    public function setLockData(object $object)
    {
        $object->updatedAt = date(self::DATE_FORMAT);
        $content = json_encode($object, JSON_PRETTY_PRINT);
        return Storage::put(self::$lockFile, $content);
    }

    public function resetLockData()
    {
        $this->setLockData((object)[
            'state' => self::STATE_QUEUED,
            'startedAt' => date(self::DATE_FORMAT),
        ]);
    }

    public function getLockData()
    {
        $content = Storage::get(self::$lockFile);
        
        if (! $content)
            return (object)self::$emptyInfo;

        $json = json_decode($content);
        if (! $json || !isset($json->state))
            return (object)self::$emptyInfo;

        if ($json->state === self::STATE_RUNNING)
            $json->progress = (int)((double)$json->current / (double)$json->total * 100);
        $json->startedAtRead = !isset($json->startedAt) ? '' : Carbon::createFromFormat(self::DATE_FORMAT, $json->startedAt)->diffForHumans();
        $json->completedAtRead = !isset($json->completedAt) ? '' : Carbon::createFromFormat(self::DATE_FORMAT, $json->completedAt)->diffForHumans();
        $json->updatedAtRead = !isset($json->updatedAt) ? '' : Carbon::createFromFormat(self::DATE_FORMAT, $json->updatedAt)->diffForHumans();
        
        return $json;
    }

    public function getInputData()
    {
        return json_decode(Storage::get(self::$inputFile));
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