<?php

namespace App\Services\Gfip\Types;

/**
 * Represents a responsible for the lot, according to line type 00.
 */
class Responsible
{
    public $type = 1;
    public $cnpj; // 14 dígitos
    public $razao_social; // <= 40 caracteres
    public $contato;

    public function __construct($jsonObject = null)
    {
        $this->contato = (object)[
            'nome' => null,
            'telefone' => null,
            'email' => null,
            'endereco' => null,
            'bairro' => null,
            'cep' => null,
            'cidade' => null,
            'uf' => null,
        ];
        
        if ($jsonObject) {
            $this->cnpj = $jsonObject->cnpj;
            $this->razao_social = $jsonObject->razao_social;
            $this->contato->nome = $jsonObject->contato->nome;
            $this->contato->telefone = $jsonObject->contato->telefone;
            $this->contato->email = $jsonObject->contato->email;
            $this->contato->endereco = $jsonObject->contato->endereco;
            $this->contato->bairro = $jsonObject->contato->bairro;
            $this->contato->cep = $jsonObject->contato->cep;
            $this->contato->cidade = $jsonObject->contato->cidade;
            $this->contato->uf = $jsonObject->contato->uf;
        }
    }
}
