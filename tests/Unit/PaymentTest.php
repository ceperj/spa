<?php

namespace Tests\Unit;

use App\Models\Person;
use App\Services\Calculators\BcNumber;
use App\Services\Calculators\InssTable;
use App\Services\Calculators\IrpfTable;
use App\Services\Calculators\Payment;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function test_payment_calculations()
    {
        $this->assertPayment('1000.00', '140.00', '0.00', '860.00');
        $this->assertPayment('1212.00', '169.68', '0.00', '1042.32');
        $this->assertPayment('1500.00', '210.00', '0.00', '1290.00');
        $this->assertPayment('1710.00', '239.40', '0.00', '1470.60');
        $this->assertPayment('2000.00', '280.00', '0.00', '1720.00');
        $this->assertPayment('3000.00', '420.00', '50.70', '2529.30');
        $this->assertPayment('4000.00', '560.00', '161.20', '3278.80');
        $this->assertPayment('5000.00', '700.00', '331.37', '3968.63');
        $this->assertPayment('6000.00', '840.00', '549.64', '4610.36');
        
        // Notorious test cases:

        // For INSS, old calculation returned 713.36 while now it is 713.37.
        // For IRPF, old was 349.84, while now it is 349.83.
        // Net salary is the same.
        $this->assertPayment('5095.44', '713.37', '349.83', '4032.24');
        
        // Same case but from 992.21 it is 992.22.
        // From 2982.78 to 2982.77.
        // Net salary is the same.
        $this->assertPayment('15000.00', '992.22', '2982.77', '11025.01');
    }

    private function assertPayment($salary, $inssMustBe, $irpfMustBe, $netSalaryMustBe)
    {
        $payment = new Payment(
            $this->createPersonModel($salary),
            $this->createIrpfTable(),
            $this->createInssTable(),
        );
        $payment->calculate();
        $this->assertEquals($salary, (string)$payment->salary, 'Bad setup for Payment class.');
        $this->assertEquals($inssMustBe, (string)$payment->inss, "A salary of $salary must have an inss of $inssMustBe, but it is $payment->inss.");
        $this->assertEquals($irpfMustBe, (string)$payment->irpf, "A salary of $salary must have an irpf of $irpfMustBe, but it is $payment->irpf.");
        $this->assertEquals($netSalaryMustBe, (string)$payment->netSalary, "A gross salary of $salary must have an net salary of $netSalaryMustBe, but it is $payment->netSalary.");
    }

    private function createPersonModel($salary) : Person
    {
        $person = new Person();
        $person->salary = str_replace('.', '', $salary);
        return $person;
    }

    private function createIrpfTable() : IrpfTable
    {
        $table = new IrpfTable();
        $table->pushIrpfModels(collect([
            (object)['min_cents'=>'0', 'max_cents'=>'190398', 'aliquot'=>'0', 'deduced_cents'=>'0'],
            (object)['min_cents'=>'190399', 'max_cents'=>'282665', 'aliquot'=>'0750', 'deduced_cents'=>'14280'],
            (object)['min_cents'=>'282666', 'max_cents'=>'375105', 'aliquot'=>'1500', 'deduced_cents'=>'35480'],
            (object)['min_cents'=>'375106', 'max_cents'=>'466468', 'aliquot'=>'2250', 'deduced_cents'=>'63613'],
            (object)['min_cents'=>'466469', 'max_cents'=>'0', 'aliquot'=>'2750', 'deduced_cents'=>'86936'],
        ]));
        return $table;
    }

    private function createInssTable() : InssTable
    {
        return new InssTable(
            BcNumber::guess('0.1400'),
            BcNumber::guess('7087.221')
        );
    }
}
