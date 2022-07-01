<?php

namespace App\Services\Gfip\Layout;

use App\Services\Gfip\Types\Bank;
use App\Services\Gfip\Types\Business;
use App\Services\Layouts\ILayoutRow;

/**
 * Represents a layout header (type 10), the business.
 */
class GfipType10 implements ILayoutRow
{
    public Business $empresa;
    public $indicador_alteracao_endereco = 'N';
    public $indicador_alteracao_cnae = 'N';
    public $aliquota_rat = null;
    public $codigo_centralizacao;
    public $simples = null;
    public $fpas;
    public $codigo_outras_entidades = null;
    public $codigo_pagamento_gps = null;
    public $percentual_isencao_filantropia = null;
    public $salario_familia = '0';
    public $salario_maternidade = '0';
    public $contrib_desc_empregado = '0';
    public $indicador_negativo_previdencia = '0';
    public $valor_devido_previdencia = '0';
    public Bank $banco;

    public function toLayoutRow(): string
    {
        $layout = [
            layout_file_number('10', 2),
            layout_file_number($this->empresa->type, 1),
            layout_file_number($this->empresa->cnpj, 14),
            layout_file_repeat('0', 36),
            layout_file_alpha($this->empresa->razao_social, 40),
            layout_file_alpha($this->empresa->endereco, 50),
            layout_file_alpha($this->empresa->bairro, 20),
            layout_file_number($this->empresa->cep, 8),
            layout_file_alpha($this->empresa->cidade, 20),
            layout_file_alpha($this->empresa->uf, 2),
            layout_file_number($this->empresa->telefone, 12),
            layout_file_alpha($this->indicador_alteracao_endereco, 1),
            layout_file_number($this->empresa->cnae, 7),
            layout_file_alpha($this->indicador_alteracao_cnae, 1),
            layout_file_number($this->aliquota_rat, 2),
            layout_file_number($this->codigo_centralizacao, 1),
            layout_file_number($this->simples, 1),
            layout_file_number($this->fpas, 3),
            layout_file_number($this->codigo_outras_entidades, 4),
            layout_file_number($this->codigo_pagamento_gps, 4),
            layout_file_number($this->percentual_isencao_filantropia, 5),
            layout_file_number($this->salario_familia, 15),
            layout_file_number($this->salario_maternidade, 15),
            layout_file_number($this->contrib_desc_empregado, 15),
            layout_file_number($this->indicador_negativo_previdencia, 1),
            layout_file_number($this->valor_devido_previdencia, 14),
            layout_file_number($this->banco->banco, 3),
            layout_file_number($this->banco->agencia, 4),
            layout_file_alpha($this->banco->conta, 9),
            layout_file_repeat('0', 45),
            layout_file_repeat(' ', 4),
            '*'
        ];
        return implode('', $layout);
    }
}