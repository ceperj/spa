<?php

namespace App\Services\Calculators;

use InvalidArgumentException;

class InssTable
{
    public BcNumber $aliquot;
    public BcNumber $ceil;
    private BcNumber $ceilDiscount;

    public function __construct(BcNumber $aliquot0to1, BcNumber $ceil)
    {
        if ($aliquot0to1->greaterThan(1))
            throw new InvalidArgumentException('Aliquot must be from 0..1 (e.g. 0.14), not a percentage base from 0..100.');
        
        $this->aliquot = $aliquot0to1;
        $this->ceil = $ceil;
        $this->ceilDiscount = $this->ceil->multiplyF($this->aliquot)->ceil(2);
    }

    /**
     * Calculates INSS discount. It clamps at ceiling.
     * 
     * @param BcNumber $salary Refers to [pt-br] "Salário bruto".
     */
    public function getInssFor(BcNumber $salary) : BcNumber
    {
        $discount = $salary->multiplyF($this->aliquot)->ceil(2);
        
        if ($discount->greaterThan($this->ceilDiscount))
            return $this->ceilDiscount;

        return $discount;
    }
}