<?php

namespace Tests\Unit;

use App\Services\Calculators\BcNumber;
use App\Services\Calculators\IrpfTable;
use PHPUnit\Framework\TestCase;

class IrpfTableTest extends TestCase
{
    public function test_instantiate_irpf_table()
    {
        $irpf = new IrpfTable();
        $irpf->pushIrpfModel($this->createRow('0.00', '999.99', '0.00', '0.00'));
        $irpf->pushIrpfModel($this->createRow('1000.00', '1999.99', '10.00', '200.00'));
        $irpf->pushIrpfModel($this->createRow('2000.00', '0.00', '20.00', '500.00'));
        $this->assertTrue(true);
    }

    public function test_retrieves_correct_aliquot()
    {
        $irpf = new IrpfTable();
        $irpf->pushIrpfModel($this->createRow('0.00', '999.99', '0.00', '0.00'));
        $irpf->pushIrpfModel($this->createRow('1000.00', '1999.99', '10.00', '200.00'));
        $irpf->pushIrpfModel($this->createRow('2000.00', '0.00', '20.00', '500.00'));
        
        $this->assertEquals('0.00', (string)$irpf->getIrpfFor(BcNumber::of('500.00', 2))->aliquotP, 'Taken aliquot should be 10% because 500 is between 0 and 999.');
        $this->assertEquals('10.00', (string)$irpf->getIrpfFor(BcNumber::of('1500.00', 2))->aliquotP, 'Taken aliquot should be 10% because 1500 is between 1000 and 1999.');
        $this->assertEquals('20.00', (string)$irpf->getIrpfFor(BcNumber::of('2500.00', 2))->aliquotP, 'Taken aliquot should be 20% because 2500 is above 2000.');

        $this->assertEquals('0.0000', (string)$irpf->getIrpfFor(BcNumber::of('500.00', 2))->aliquot, 'Taken aliquot should be 10% because 500 is between 0 and 999.');
        $this->assertEquals('0.1000', (string)$irpf->getIrpfFor(BcNumber::of('1500.00', 2))->aliquot, 'Taken aliquot should be 10% because 1500 is between 1000 and 1999.');
        $this->assertEquals('0.2000', (string)$irpf->getIrpfFor(BcNumber::of('2500.00', 2))->aliquot, 'Taken aliquot should be 20% because 2500 is above 2000.');
    }

    public function test_sort_correctly()
    {
        $irpf = new IrpfTable();
        $irpf->pushIrpfModels(collect([
            $this->createRow('2000.00', '2999.00', '20.00', '500.00'),
            $this->createRow('3000.00', '0.00', '30.00', '800.00'),
            $this->createRow('0.00', '999.99', '0.00', '0.00'),
            $this->createRow('1000.00', '1999.99', '10.00', '200.00'),
        ]));
        $this->assertEquals('0.00', (string)$irpf->getIrpfFor(BcNumber::of('500.00', 2))->aliquotP, 'Taken aliquot should be 10% because 500 is between 0 and 999.');
        $this->assertEquals('10.00', (string)$irpf->getIrpfFor(BcNumber::of('1500.00', 2))->aliquotP, 'Taken aliquot should be 10% because 1500 is between 1000 and 1999.');
        $this->assertEquals('20.00', (string)$irpf->getIrpfFor(BcNumber::of('2500.00', 2))->aliquotP, 'Taken aliquot should be 20% because 2500 is between 2000 and 2999.');
        $this->assertEquals('30.00', (string)$irpf->getIrpfFor(BcNumber::of('10500.00', 2))->aliquotP, 'Taken aliquot should be 20% because 10500 is above 3000.');
    }

    public function test_real_scenarios_2022()
    {
        $irpf = new IrpfTable();
        $irpf->pushIrpfModels(collect([
            $this->createRow('0.00', '1903.98', '0.00', '0.00'),
            $this->createRow('1903.99', '2826.65', '7.50', '142.80'),
            $this->createRow('2826.66', '3751.05', '15.00', '354.80'),
            $this->createRow('3751.06', '4664.68', '22.50', '636.13'),
            $this->createRow('4664.69', '0.00', '27.50', '869.36'),
        ]));

        $this->assertEquals('0.0000', (string)$irpf->getIrpfFor(BcNumber::of('1900', 2))->aliquot);
        $this->assertEquals('0.0000', (string)$irpf->getIrpfFor(BcNumber::of('1903.98', 2))->aliquot);
        $this->assertEquals('0.0750', (string)$irpf->getIrpfFor(BcNumber::of('1903.99', 2))->aliquot);
        $this->assertEquals('0.1500', (string)$irpf->getIrpfFor(BcNumber::of('2826.66', 2))->aliquot);
    }

    private function createRow(string $min_cents, string $max_cents, string $aliquot, string $deduced_cents)
    {
        return (object)[
            'min_cents' => $this->preg_number($min_cents),
            'max_cents' => $this->preg_number($max_cents),
            'aliquot' => $this->preg_number($aliquot),
            'deduced_cents' => $this->preg_number($deduced_cents),
        ];
    }
    
    private function preg_number(string $subject)
    {
        preg_match('/^(\d+)\.(\d\d)$/', $subject, $matches);
        return $matches[1] . $matches[2];
    }
}
