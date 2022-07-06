<?php

namespace App\Services\Calculators;

use App\Models\Irpf;
use Exception;
use Illuminate\Support\Collection;

/**
 * Stores temporarily a table for IRPF checking, to be used during the payments
 * calculation. This class does not store any permanent data.
 */
class IrpfTable
{
    private Collection $table;

    public function __construct()
    {
        $this->table = new Collection();
    }

    /**
     * Creates a new instance of this class and retrieve its data from the
     * database records (only non-deleted ones).
     */
    public static function fromDatabase() : IrpfTable
    {
        $instance = new IrpfTable();
        $instance->pushIrpfModels(Irpf::all());
        return $instance;
    }

    /**
     * Push a collection of models to this table, then sort it by salary range
     * and check consistency.
     */
    public function pushIrpfModels(Collection $models)
    {
        foreach($models as $model)
        {
            $this->pushIrpfModel($model, false);
        }
        $this->sortTable();
        $this->checkConsistency();
    }

    /**
     * Push one model to this table. By default it sort its salary range and
     * check the consistency, but for batch push it is possible to disable this
     * behaviour.
     */
    public function pushIrpfModel($model, $sortAndCheck = true)
    {
        $this->table->push((object)[
            'min' => BcNumber::of($model->min_cents, 0)->divideBy10E(2),
            'max' => $model->max_cents == 0 ? null : BcNumber::of($model->max_cents, 0)->divideBy10E(2),
            'aliquotP' => BcNumber::of($model->aliquot, 0)->divideBy10E(2),
            'aliquot' => BcNumber::of($model->aliquot, 0)->divideBy10E(4),
            'deduced' => BcNumber::of($model->deduced_cents, 0)->divideBy10E(2),
        ]);

        if (! $sortAndCheck)
            return;
        
        $this->sortTable();
        $this->checkConsistency();
    }

    /**
     * Get the IRPF range for the given salary.
     * 
     * @param BcNumber $salaryExceptInss Refers to total salary _after_ INSS subtraction.
     */
    public function getIrpfFor(BcNumber $salaryExceptInss)
    {
        foreach($this->table as $range)
        {
            if ($this->isRangeFor($salaryExceptInss, $range))
                return $range;
        }

        $this->checkConsistency();
        $this->throwTableError('There is some undetected problem with the table.');
    }

    private function isRangeFor(BcNumber $salaryExceptInss, $range)
    {
        if ($salaryExceptInss->lowerThan($range->min))
            return false;
        
        // This range is the wide open maximum.
        if ($range->max === null)
            return true;
        
        if ($salaryExceptInss->lowerOrEqualsTo($range->max))
            return true;
        
        return false;
    }

    private function sortTable()
    {
        $this->table = $this->table->sortBy('min')->values();
    }

    /**
     * Returns true if the table is correctly formed, or a string displaying
     * an error if the table is inconsistent.
     * 
     * @return string|true
     */
    private function consistent() : string|bool
    {
        $previousMin = -1;
        $previousMax = -1;
        $lastRangeReached = false;
        $first = true;
        foreach($this->table as $range)
        {
            if ($first && ! $range->min->equalsTo(0))
                return 'First range must always start at 0 (min=0).';
            
            $first = false;

            if ($range->min->lowerOrEqualsTo($previousMin))
                return 'Table is not sorted incrementally (see column `min`)';

            if ($lastRangeReached)
                return 'A range was defined after the wide open range (max=0).';
            
            if ($range->min->lowerThan($previousMax))
                return 'The current range\'s min is lower than the previous range\'s max.';

            if ($range->max === null)
            {
                $lastRangeReached = true;
                continue;
            }

            if ($range->min->greaterThan($range->max))
                return 'Range is inverted, min is greater than max';

            $previousMin = $range->min;
            $previousMax = $range->max;
        }

        return true;
    }

    private function checkConsistency()
    {
        $consistent = $this->consistent();
        if ($consistent === true)
            return;

        $this->throwTableError($consistent);
    }

    private function throwTableError($message)
    {
        $errorMessage = collect([$message, '', 'IRPF table is mounted as follow:']);
        foreach($this->table as $range)
            $errorMessage->push("  [$range->min..$range->max] ($range->aliquot, $range->deduced)");
        throw new Exception($errorMessage->implode("\n"));
    }
}