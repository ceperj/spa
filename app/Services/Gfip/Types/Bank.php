<?php

namespace App\Services\Gfip\Types;

/**
 * Represents a business, according to header type 10.
 */
class Bank
{
    public $banco; // 3 dígitos
    public $agencia; // 4 dígitos (sem código verificador)
    public $conta; // <= 9 caracteres
    
    public function __construct($jsonObject = null)
    {
        if ($jsonObject) {
            $this->banco = $jsonObject->banco;
            $this->agencia = $jsonObject->agencia;
            $this->conta = $jsonObject->conta;
        }
    }
}
