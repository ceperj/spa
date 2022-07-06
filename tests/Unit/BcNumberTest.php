<?php

namespace Tests\Unit;

use App\Services\Calculators\BcNumber;
use PHPUnit\Framework\TestCase;

class BcNumberTest extends TestCase
{
    public function test_string_to_number_and_number_to_string()
    {
        $num1 = new BcNumber('1234.5678', 3);
        $num2 = BcNumber::of('1234.5678', 3);
        $this->assertEquals('1234.567', (string)$num1);
        $this->assertEquals('1234.567', (string)$num2);
    }

    public function test_addition()
    {
        $result = BcNumber::of('1', 3)->add(5, new BcNumber('5.1', 1), '5.2');
        $expected = '16.300';
        $this->assertEquals($expected, (string)$result);
    }

    public function test_subtraction()
    {
        $result = BcNumber::of('16.3', 5)->subtract(5, new BcNumber('5.1', 1), '5.2');
        $expected = '1.00000';
        $this->assertEquals($expected, (string)$result);
    }

    public function test_multiplication()
    {
        $result = BcNumber::of('2', 1)->multiplyBy(2, new BcNumber('2.1', 2), '2.2');
        $expected = '18.4'; // 18.48
        $this->assertEquals($expected, (string)$result);
    }

    public function test_division()
    {
        $result = BcNumber::of('8', 1)->divideBy(2, new BcNumber('2', 1), '2', '2');
        $expected = '0.5';
        $this->assertEquals($expected, (string)$result);
    }

    public function test_shift_decimal_place()
    {
        $result = BcNumber::of('15', 0)->divideBy10E(2);
        $expected = '0.15';
        $this->assertEquals($expected, (string)$result);

        $result = BcNumber::of('1500', 0)->divideBy10E(4);
        $expected = '0.1500';
        $this->assertEquals($expected, (string)$result);

        $result = BcNumber::of('250.3', 1)->divideBy10E(5);
        $expected = '0.002503';
        $this->assertEquals($expected, (string)$result);
    }

    public function test_comparision()
    {
        $this->assertTrue(BcNumber::of('10', 0)->equalsTo('10.00'), 'equalsTo not working');

        $this->assertFalse(BcNumber::of('10', 0)->notEqualsTo('10.00'), 'notEqualsTo not working');
        $this->assertTrue(BcNumber::of('10', 0)->notEqualsTo('10.001'), 'notEqualsTo not working for mixed scale');

        $this->assertFalse(BcNumber::of('10', 0)->greaterThan('10'), 'greaterThan not working');
        $this->assertTrue(BcNumber::of('10', 0)->greaterThan('9'), 'greaterThan not working');
        
        $this->assertTrue(BcNumber::of('10', 0)->greaterOrEqualsTo('9'), 'greaterOrEqualsTo not working');
        $this->assertTrue(BcNumber::of('10', 0)->greaterOrEqualsTo('10'), 'greaterOrEqualsTo not working');
        
        $this->assertFalse(BcNumber::of('10', 0)->lowerThan('10'), 'lowerThan not working');
        $this->assertTrue(BcNumber::of('10', 0)->lowerThan('11'), 'lowerThan not working');
        $this->assertTrue(BcNumber::of('10', 0)->lowerThan('10.2'), 'lowerThan not working for mixed scale');
        
        $this->assertTrue(BcNumber::of('10', 0)->lowerOrEqualsTo('11'), 'lowerOrEqualsTo not working');
        $this->assertTrue(BcNumber::of('10', 0)->lowerOrEqualsTo('10'), 'lowerOrEqualsTo not working');
        $this->assertTrue(BcNumber::of('10', 0)->lowerOrEqualsTo('10.2'), 'lowerOrEqualsTo not working for mixed scale');
    }

    public function test_guess_scale()
    {
        $this->assertEquals('0', (string)BcNumber::guess('-0'));
        $this->assertEquals('0.0', (string)BcNumber::guess('-0.0'));
        $this->assertEquals('1', (string)BcNumber::guess('1'));
        $this->assertEquals('0.5', (string)BcNumber::guess('0.5'));
        $this->assertEquals('0.000001', (string)BcNumber::guess('0.000001'));
        $this->assertEquals('5', (string)BcNumber::guess(5));
        $this->assertEquals('0.5', (string)BcNumber::guess(0.5));
    }

    public function test_is_positive()
    {
        $this->assertTrue(BcNumber::guess('0')->isPositive());
        $this->assertTrue(BcNumber::guess('5')->isPositive());
        $this->assertFalse(BcNumber::guess('-5')->isPositive());
        $this->assertTrue(BcNumber::guess('-0')->isPositive());
        $this->assertTrue(BcNumber::of('0.00001', 5)->isPositive());
        $this->assertFalse(BcNumber::of('-0.00001', 5)->isPositive());
        $this->assertTrue(BcNumber::of('-0.00001', 4)->isPositive()); // due to precision loss
    }
    
    public function test_is_negative()
    {
        $this->assertTrue(BcNumber::guess('-5')->isNegative());
        $this->assertTrue(BcNumber::of('-1', 5)->isNegative());
        $this->assertTrue(BcNumber::of('-0.00001', 5)->isNegative());
        $this->assertFalse(BcNumber::of('-0.00001', 4)->isNegative()); // due to precision loss
        $this->assertFalse(BcNumber::guess('5')->isNegative());
        $this->assertFalse(BcNumber::guess('0')->isNegative());
        $this->assertFalse(BcNumber::guess('-0')->isNegative());
    }

    public function test_negate()
    {
        $this->assertEquals('0.00', BcNumber::guess('-0.00')->negate());
        $this->assertEquals('0.00', BcNumber::guess('0.00')->negate());
        $this->assertEquals('-1.234', BcNumber::guess('1.234')->negate());
        $this->assertEquals('1.234', BcNumber::guess('-1.234')->negate());
    }

    public function test_abs()
    {
        $this->assertEquals('0', (string)BcNumber::guess('0')->abs());
        $this->assertEquals('10', (string)BcNumber::guess('10')->abs());
        $this->assertEquals('10', (string)BcNumber::guess('-10')->abs());
        $this->assertEquals('1', (string)BcNumber::guess('1')->abs());
        $this->assertEquals('1', (string)BcNumber::guess('-1')->abs());
        $this->assertEquals('0.000001', (string)BcNumber::guess('0.000001')->abs());
        $this->assertEquals('0.000001', (string)BcNumber::guess('-0.000001')->abs());
        $this->assertEquals('0.000', (string)BcNumber::of('-0.000001', 3)->abs());
    }

    public function test_with_scale()
    {
        $this->assertEquals('0.00', (string)BcNumber::guess('0')->withScale(2));
        $this->assertEquals('1.2', (string)BcNumber::guess('1.234')->withScale(1));
        $this->assertEquals('1.23400', (string)BcNumber::guess('1.234')->withScale(5));
    }

    public function test_round()
    {
        $this->assertEquals('0.00', (string)BcNumber::guess('0.001')->round(2));
        $this->assertEquals('8', (string)BcNumber::guess('7.50')->round(0));
        $this->assertEquals('8', (string)BcNumber::guess('7.60')->round(0));
        $this->assertEquals('7', (string)BcNumber::guess('7.40')->round(0));
        $this->assertEquals('7', (string)BcNumber::guess('7.49')->round(0));
        $this->assertEquals('0.001', (string)BcNumber::guess('0.001')->round(3));
        $this->assertEquals('0.0010', (string)BcNumber::guess('0.001')->round(4));
        $this->assertEquals('-7', (string)BcNumber::guess('-7.49')->round(0));
        $this->assertEquals('-8', (string)BcNumber::guess('-7.50')->round(0));
        $this->assertEquals('-8', (string)BcNumber::guess('-7.6')->round(0));
    }

    public function test_floor()
    {
        $this->assertEquals('0.00', (string)BcNumber::guess('0.001')->floor(2));
        $this->assertEquals('0.01', (string)BcNumber::guess('0.019')->floor(2));
        $this->assertEquals('0.0100', (string)BcNumber::guess('0.01')->floor(4));
        $this->assertEquals('5', (string)BcNumber::guess('5.9999')->floor(0));

        $this->assertEquals('0.00', (string)BcNumber::guess('-0.001')->floor(2));
        $this->assertEquals('-0.01', (string)BcNumber::guess('-0.019')->floor(2));
        $this->assertEquals('-0.0100', (string)BcNumber::guess('-0.01')->floor(4));
        $this->assertEquals('-5', (string)BcNumber::guess('-5.9999')->floor(0));
    }

    public function test_ceil()
    {
        $this->assertEquals('820.40', (string)BcNumber::guess('820.3958')->ceil(2));

        $this->assertEquals('0.01', (string)BcNumber::guess('0.00005')->ceil(2));
        $this->assertEquals('0.01', (string)BcNumber::guess('0.001')->ceil(2));
        $this->assertEquals('1', (string)BcNumber::guess('0.001')->ceil(0));
        $this->assertEquals('1', (string)BcNumber::guess('1')->ceil(0));
        $this->assertEquals('2', (string)BcNumber::guess('1.1')->ceil(0));

        $this->assertEquals('-0.01', (string)BcNumber::guess('-0.00005')->ceil(2));
        $this->assertEquals('-0.02', (string)BcNumber::guess('-0.019')->ceil(2));
        $this->assertEquals('-0.01', (string)BcNumber::guess('-0.001')->ceil(2));
        $this->assertEquals('-1', (string)BcNumber::guess('-0.001')->ceil(0));
        $this->assertEquals('-1', (string)BcNumber::guess('-1')->ceil(0));
        $this->assertEquals('-2', (string)BcNumber::guess('-1.1')->ceil(0));
    }

    public function test_add_floating()
    {
        $this->assertEquals('0.5', (string)BcNumber::guess('0.25')->addF('0.25'));
        $this->assertEquals('0.5', (string)BcNumber::guess('0.25000')->addF('0.25000'));
        $this->assertEquals('0.50001', (string)BcNumber::guess('0.2500')->addF('0.25001'));
    }

    public function test_sub_floating()
    {
        $this->assertEquals('0', (string)BcNumber::guess('0.500')->subtractF('0.5'));
        $this->assertEquals('0.25', (string)BcNumber::guess('0.5')->subtractF('0.2500'));
    }

    public function test_muliply_floating()
    {
        $this->assertEquals('5', (string)BcNumber::guess('2')->multiplyF('2.5'));
        $this->assertEquals('0.0625', (string)BcNumber::guess('0.25')->multiplyF('0.25'));
        $this->assertEquals('820.3958', (string)BcNumber::guess('5859.97')->multiplyF('0.14'));
        $this->assertEquals('820.40', (string)BcNumber::guess('5859.97')->multiplyF('0.14')->ceil(2));
    }

    public function test_divide_floating()
    {
        $this->assertEquals('0.3333333333', (string)BcNumber::guess('1')->divideF('3'));
        $this->assertEquals('0.334', (string)BcNumber::guess('1')->divideF('3')->ceil(3));
        $this->assertEquals('0.333', (string)BcNumber::guess('1')->divideF('3')->floor(3));
        $this->assertEquals('1', (string)BcNumber::guess('1')->divideF('3')->ceil());
    }
}
