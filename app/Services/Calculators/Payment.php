<?php

namespace App\Services\Calculators;

use App\Models\Person;
use Exception;

class Payment
{
    private Person $person;
    private IrpfTable $irpfTable;
    private InssTable $inssTable;
    public BcNumber $salary;
    public BcNumber $netSalary;
    public BcNumber $inss;
    public BcNumber $irpf;
    public object $irpfRange;

    function __construct(Person $person, IrpfTable $irpfTable, InssTable $inssTable)
    {
        $this->person = $person;
        $this->irpfTable = $irpfTable;
        $this->inssTable = $inssTable;
    }

    public function calculate()
    {
        $this->salary = BcNumber::of($this->person->salary, 0)->divideBy10E(2);
        $this->calculateInss();
        $this->calculateIrpf();
        $this->calculateNetSalary();
    }

    private function calculateNetSalary()
    {
        $this->netSalary = $this->salary
            ->subtract($this->inss)
            ->subtract($this->irpf);
    }

    private function calculateIrpf()
    {
        if ($this->inss === null)
            throw new Exception('IRPF calculation depends on INSS, which has not been calculated.');

        $taxable = $this->salary->subtract($this->inss);
        $this->irpfRange = $this->irpfTable->getIrpfFor($taxable);
        $aliquotTax = $taxable->multiplyBy($this->irpfRange->aliquot);
        $deducedTax = $this->irpfRange->deduced;
        $tax = $aliquotTax->subtract($deducedTax)->clamp(0, null);
        $this->irpf = $tax;
    }

    private function calculateInss()
    {
        $this->inss = $this->inssTable->getInssFor($this->salary);
    }
}
