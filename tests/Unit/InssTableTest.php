<?php

namespace Tests\Unit;

use App\Services\Calculators\BcNumber;
use App\Services\Calculators\InssTable;
use PHPUnit\Framework\TestCase;

class InssTableTest extends TestCase
{
    public function test_constructor_requires_percentage_from_0_to_1()
    {
        $this->expectErrorMessage('Aliquot must be from 0..1 (e.g. 0.14), not a percentage base from 0..100');
        new InssTable(BcNumber::of('14', 2), BcNumber::of('7000', 2));
    }

    public function test_get_inss_for_value_below_ceiling()
    {
        $salary = new BcNumber('1500.01', 2);
        $aliquot = new BcNumber('0.5', 2);
        $ceil = new BcNumber('2000', 2);
        $expected = '750.01'; // 750.005 for $salary
        $inss = new InssTable($aliquot, $ceil);
        $this->assertEquals($expected, (string)$inss->getInssFor($salary));
    }

    public function test_get_inss_for_value_above_ceiling()
    {
        $salary = new BcNumber('1500.01', 2);
        $aliquot = new BcNumber('0.5', 2);
        $ceil = new BcNumber('1000.01', 2);
        $expected = '500.01'; // 500.005 for $ceil
        $inss = new InssTable($aliquot, $ceil);
        $this->assertEquals($expected, (string)$inss->getInssFor($salary));
    }

    public function test_number_precision()
    {
        $salary = new BcNumber('5859.97', 2);
        $aliquot = new BcNumber('0.14', 2);
        $ceil = new BcNumber('7000', 2);
        $inss = new InssTable($aliquot, $ceil);
        $this->assertEquals('820.40', (string)$inss->getInssFor($salary));
    }
}