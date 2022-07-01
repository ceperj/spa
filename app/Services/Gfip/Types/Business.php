<?php

namespace App\Services\Gfip\Types;

/**
 * Represents a business, according to header type 10.
 */
class Business
{
    public $type = 1;
    public $cnpj; // 14 dígitos
    public $razao_social; // <= 40 caracteres
    public $endereco; // <= 50 caracteres
    public $bairro; // <= 20 caracteres
    public $cep; // 8 dígitos
    public $cidade; // <= 20 caracteres
    public $uf; // 2 caracteres
    public $telefone; // 12 dígitos
    public $cnae; // 7 dígitos
    public ?Bank $banco = null;

    public function __construct($jsonObject = null)
    {
        if ($jsonObject) {
            $this->cnpj = $jsonObject->cnpj;
            $this->razao_social = $jsonObject->razao_social;
            $this->endereco = $jsonObject->endereco;
            $this->bairro = $jsonObject->bairro;
            $this->cep = $jsonObject->cep;
            $this->cidade = $jsonObject->cidade;
            $this->uf = $jsonObject->uf;
            $this->telefone = $jsonObject->telefone;
            $this->cnae = $jsonObject->cnae;

            if (isset($jsonObject->banco) && !! $jsonObject->banco)
                $this->banco = new Bank($jsonObject->banco);
        }
    }
}
