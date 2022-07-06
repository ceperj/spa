<?php
namespace App\Services\Calculators;

use Closure;
use InvalidArgumentException;

/**
 * Wrapper for a number of bcmath library, it is simpler than webit/bcmath, and
 * more advanced than Brick\...\BcMathCalculator.
 */
class BcNumber
{
    private string $value;
    private int $scale;

    /**
     * Create a new instance of the BcNumber.
     * 
     * Invoke as:
     * - `new BcNumber($anotherBcNumber)`
     * - `new BcNumber($string, $scale)`
     * - `new BcNumber($integer, $scale)`
     */
    public function __construct(mixed $number, ?int $scale = null)
    {
        if ($number instanceof self)
            $this->createFromInstance($number);
        else if ($scale === null)
            throw new InvalidArgumentException('BcNumber::$scale must not be null.');
        else
            $this->createFromLiteral($number, $scale);
    }

    /**
     * Sugar syntax of `new BcNumber($number[, $scale])`.
     */
    public static function of(mixed $number, ?int $scale=null) : BcNumber
    {
        return new BcNumber($number, $scale);
    }

    public static function guess($number) : BcNumber
    {
        if ($number instanceof BcNumber)
            return $number;

        $instance = new BcNumber('0', 0);
        $instance->scale = $instance->calculateScale((string)$number);
        $instance->value = bcadd((string)$number, '0', $instance->scale);
        return $instance;
    }

    /**
     * Returns the sum of this number with a number or a sequence of numbers.
     * 
     * Each argument can be of type BcNumber or string.
     */
    public function add(...$values) : BcNumber
    {
        $result = $this->forMany($values, fn ($a, $b, $scale) => bcadd($a,$b,$scale));
        return new BcNumber($result, $this->scale);
    }
    
    /**
     * Returns the subtraction of this number by a number or a sequence of
     * numbers.
     * 
     * Each argument can be of type BcNumber or string.
     */
    public function subtract(...$values) : BcNumber
    {
        $result = $this->forMany($values, fn ($a, $b, $scale) => bcsub($a, $b, $scale));
        return new BcNumber($result, $this->scale);
    }

    /**
     * Returns the multiplication of this number by a number or a sequence of
     * numbers.
     * 
     * Each argument can be of type BcNumber or string. 
     */
    public function multiply(...$multipliers) : BcNumber
    {
        $result = $this->forMany($multipliers, fn ($a, $b, $scale) => bcmul($a, $b, $scale));
        return new BcNumber($result, $this->scale);
    }

    /**
     * Alias for multiply.
     */
    public function multiplyBy(...$multipliers) : BcNumber
    {
        return $this->multiply(...$multipliers);
    }

    /**
     * Returns the division of this number by a number or a sequence of
     * numbers.
     * 
     * Each argument can be of type BcNumber or string.
     */
    public function divide(...$divisors) : BcNumber
    {
        $result = $this->forMany($divisors, fn ($a, $b, $scale) => bcdiv($a, $b, $scale));
        return new BcNumber($result, $this->scale);
    }

    /**
     * Alias for divide.
     */
    public function divideBy(...$divisors) : BcNumber
    {
        return $this->divide(...$divisors);
    }

    /**
     * Move the decimal separator to left, increasing the decimal part while
     * taking numbers from the integer part. Useful to make an integer turn
     * into a decimal, given the last digits are the decimal part.
     * 
     * In practice, it return this number divided by `10 ^ $times`.
     * 
     * Examples below may clarify some behaviors:
     * - `new BcNumber(1234, 0)->divideBy10E(0)` throws
     * - `new BcNumber(1234, 0)->divideBy10E(1)` is `123.4`
     * - `new BcNumber(1234, 0)->divideBy10E(2)` is `12.34`
     * - `new BcNumber(1234, 0)->divideBy10E(3)` is `1.234`
     * - `new BcNumber(1234, 0)->divideBy10E(4)` is `0.1234`
     * - `new BcNumber(1234, 0)->divideBy10E(5)` is `0.12340`
     * - `new BcNumber(1234, 1)->divideBy10E(5)` is `0.123400`
     * - `new BcNumber(1234, 2)->divideBy10E(5)` is `0.1234000`
     * - `new BcNumber('12.34', 2)->divideBy10E(1)` is `1.234`
     * - `new BcNumber('12.34', 2)->divideBy10E(2)` is `0.1234`
     */
    public function divideBy10E(int $times) : BcNumber
    {
        $value = '1' . str_repeat('0', $times);
        $this->scale += $times;
        return $this->divideBy($value);
    }

    /**
     * Sum, but result vary on scale.
     */
    public function addF($other) : BcNumber
    {
        $other = BcNumber::guess($other);
        $varyingScale = $this->largestScaleBetweenMineAnd($other);
        $result = bcadd($this->value, (string)$other, $varyingScale);
        $result = rtrim($result, '0');
        return BcNumber::guess($result);
    }

    /**
     * Subtract, but result vary on scale.
     */
    public function subtractF($other) : BcNumber
    {
        $other = BcNumber::guess($other);
        $varyingScale = $this->largestScaleBetweenMineAnd($other);
        $result = bcsub($this->value, (string)$other, $varyingScale);
        $result = rtrim($result, '0');
        return BcNumber::guess($result);
    }
    
    /**
     * Multiplication, but result vary on scale.
     */
    public function multiplyF($multiplier) : BcNumber
    {
        $multiplier = BcNumber::guess($multiplier);
        $varyingScale = $this->scale + $multiplier->scale;
        $result = bcmul($this->value, (string)$multiplier, $varyingScale);
        $result = rtrim($result, '0');
        return BcNumber::guess($result);
    }
    
    /**
     * Division, but result vary on scale limited to a max precision. Any digit
     * after the max precision is removed (like in `floor`).
     */
    public function divideF($divisor, $maxPrecision = 10) : BcNumber
    {
        $divisor = BcNumber::guess($divisor);
        $result = bcdiv($this->value, (string)$divisor, $maxPrecision);
        $result = rtrim($result, '0');
        return BcNumber::guess($result);
    }

    /**
     * Returns a new BcNumber with the scale given by parameter.
     * @param int $scale The new decimal scale.
     * @return BcNumber A new instance of the number.
     */
    public function withScale(int $scale) : BcNumber
    {
        return new BcNumber($this->value, $scale);
    }

    /**
     * Round the number to given scale. If the scale is greater than current,
     * return the number with the new scale filled with '0' at end. Otherwise,
     * it acts as a round.
     * 
     * Refer to `round(float $num, int $precision, PHP_ROUND_HALF_UP)` to learn
     * the overall behaviour. Notice this function DOES NOT use `round`.
     * 
     * Notice negative numbers behave like positive numbers with `-`.
     */
    public function round(int $scale = 0) : BcNumber
    {
        if ($scale >= $this->scale)
            return $this->withScale($scale);
        
        $unfoldedScale = '0.' . str_repeat('0', $scale) . '5';
        $result = $this->abs()->add($unfoldedScale)->withScale($scale);
        return $this->isNegative() ? $result->negate() : $result;
    }

    /**
     * Rounds fraction up to the requested scale.
     * 
     * Notice negative numbers behave like positive numbers with `-`.
     */
    public function ceil(int $scale = 0) : BcNumber
    {
        if ($scale >= $this->scale)
            return $this->withScale($scale);

        $unfoldedScale = $scale === 0 ? '1' : '0.'.str_repeat('0', $scale - 1).'1';
        $result = $this->abs()->add($unfoldedScale)->withScale($scale);
        return $this->isNegative() ? $result->negate() : $result;
    }

    /**
     * Rounds fraction down. It just remove the non-included decimal scale, like
     * `withScale` would do.
     * 
     * Notice negative numbers behave like positive numbers with `-`.
     */
    public function floor(int $scale = 0) : BcNumber
    {
        return $this->withScale($scale);
    }

    /**
     * In short, returns number as positive.
     */
    public function abs() : BcNumber
    {
        if ($this->isNegative())
            return new BcNumber(substr($this->value, 1), $this->scale);
        
        return $this;
    }

    /**
     * Invert the signal.
     */
    public function negate()
    {
        if ($this->isNegative())
            return $this->abs();
        return new BcNumber('-' . $this->value, $this->scale);
    }

    /**
     * Tell if the number is lower than 0.
     */
    public function isNegative() : bool
    {
        return $this->value[0] === '-';
    }

    /**
     * Tell if the number is greater or equal to 0.
     */
    public function isPositive() : bool
    {
        return ! $this->isNegative();
    }

    public function equalsTo($value) : bool
    {
        $scale = $this->largestScaleBetweenMineAnd($value);        
        $result = bccomp($this->value, $value, $scale);
        return $result == 0;
    }

    public function notEqualsTo($value) : bool
    {
        $scale = $this->largestScaleBetweenMineAnd($value);        
        $result = bccomp($this->value, $value, $scale);
        return $result != 0;
    }

    public function greaterThan($value) : bool
    {
        $scale = $this->largestScaleBetweenMineAnd($value);        
        $result = bccomp($this->value, $value, $scale);
        return $result > 0;
    }

    public function greaterOrEqualsTo($value) : bool
    {
        $scale = $this->largestScaleBetweenMineAnd($value);        
        $result = bccomp($this->value, $value, $scale);
        return $result >= 0;
    }

    public function lowerThan($value) : bool
    {
        $scale = $this->largestScaleBetweenMineAnd($value);        
        $result = bccomp($this->value, $value, $scale);
        return $result < 0;
    }

    public function lowerOrEqualsTo($value) : bool
    {
        $scale = $this->largestScaleBetweenMineAnd($value);        
        $result = bccomp($this->value, $value, $scale);
        return $result <= 0;
    }

    /**
     * Clamp the number between a minimum and a maximum value.
     * 
     * If $min or $max are returned, they will be a BcNumber with the same
     * scale as $this, wherever they are BcNumbers, strings or integers.
     * 
     * @param BcNumber|string|null $min The minimum value the number is allowed
     * to be. Pass null for infinity negative.
     * @param BcNumber|string|null $max The maximum value the number is allowed
     * to be. Pass null for infinity positive.
     * @return BcNumber The number clamped between $min and $max.
     */
    public function clamp($min, $max) : BcNumber
    {
        if (($min !== null) && $this->lowerThan($min))
            return BcNumber::guess($min)->withScale($this->scale);
        if (($max !== null) && $this->greaterThan($max))
            return BcNumber::guess($max)->withScale($this->scale);
        return $this;
    }

    /**
     * Returns the number without the decimal separator, but _including_ the
     * decimal segment. The last digits of the integer will be the decimal
     * places according to $scale.
     * 
     * If $scale is greater than the number scale, digits '0' will be added. If
     * the $scale is lower than the number scale, the digits will be removed.
     * 
     * @param int|null $scale Scale as in `bcmath` scale. If null, uses the scale of this number.
     * @return string Digits without decimal separator. May include negative sign if number is negative.
     */
    public function toIntegerString(?int $scale = null) : string
    {
        if ($scale === null)
            $scale = $this->scale;
        
        $regenerated = bcadd($this->value, '0', $scale);
        return str_replace('.', '', $regenerated);
    }

    /**
     * Returns the value as string.
     */
    public function __toString()
    {
        return $this->value;
    }

    private function createFromInstance($instance)
    {
        $this->value = $instance->value;
        $this->scale = $instance->scale;
    }

    private function createFromLiteral(mixed $number, int $scale)
    {
        $this->value = bcadd($number, '0', $scale);
        $this->scale = (int)$scale;
    }

    private function forMany(array $values, Closure $operation)
    {
        $result = $this->value;
        foreach($values as $value)
        {
            $result = $operation->__invoke($result, (string)$value, $this->scale);
        }
        return $result;
    }

    private function largestScaleBetweenMineAnd($value) : int
    {
        $valueScale = $value instanceof BcNumber
            ? $value->scale
            : self::calculateScale($value);

        return $this->scale >= $valueScale
            ? $this->scale
            : $valueScale;
    }

    static private function calculateScale(string $value) : int
    {
        $parts = explode('.', (string)$value);
        return count($parts) > 1 ? strlen($parts[1]) : 0;
    }
}