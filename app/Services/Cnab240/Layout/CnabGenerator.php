<?php

namespace App\Services\Cnab240\Layout;

/**
 * Main class for Cnab240. Create a new instance, set header and footer as
 * corresponding objects, and append all payments to the lot. Then call
 * `toString` method and save the result to file.
 * 
 * @remarks Adapted from https://github.com/thiagosantos/bradesco-cnab240 (Python).
 */
class CnabGenerator
{
    private $header;
    private $payments = [];
    private $footer;

    /**
     * Set file header.
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * Append a new payment to the lot.
     * 
     * @param Payment $payment The payment instance.
     */
    public function append(Payment $payment)
    {
        array_push($this->payments, $payment);
    }

    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    public function toString()
    {
        $lines = [];
        array_push($lines, $this->header->toString());
        foreach ($this->payments as $payment) {
            array_push($lines, $payment->toString());
        }
        array_push($lines, $this->footer->toString());
        return implode("\n", $lines) . "\n";
    }

    public function __toString()
    {
        return $this->toString();
    }
}
