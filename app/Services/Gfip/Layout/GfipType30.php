<?php

namespace App\Services\Gfip\Layout;

use App\Services\Gfip\Types\Business;
use App\Services\Gfip\Types\EmployeeType13;
use App\Services\Layouts\ILayoutRow;

/**
 * Represents a layout line type 30, the employee.
 * For now, this record type supports only employees type 13.
 */
class GfipType30 implements ILayoutRow
{
    public Business $empresa;
    public $tipo_inscricao_obra_civil = null;
    public $inscricao_obra_civil = null;
    public EmployeeType13 $trabalhador;
    public $numero_ctps = null;
    public $serie_ctps = null;
    public $data_opcao = null;
    public $remuneracao_sem_13o;
    public $remuneracao_13o;
    public $classe_contribuicao = null;
    public $ocorrencia = '05';
    public $valor_descontado_segurado;
    public $base_calculo_prevsocial;
    public $base_calculo_13o_prevsocial_movimento;
    public $base_calculo_13o_prevsocial_gps;

    public function toLayoutRow(): string
    {
        $layout = [
            layout_file_number('30', 2),
            layout_file_number($this->empresa->type, 1),
            layout_file_number($this->empresa->cnpj, 14),
            layout_file_number($this->tipo_inscricao_obra_civil, 1),
            layout_file_number($this->inscricao_obra_civil, 14),
            layout_file_number($this->trabalhador->pis_pasep_ci, 11),
            layout_file_date_yyyy($this->trabalhador->data_admissao, 8),
            layout_file_number($this->trabalhador->categoria_trabalhador, 2),
            layout_file_alpha($this->trabalhador->nome_trabalhador, 70),
            layout_file_number($this->trabalhador->matricula_empregado, 11),
            layout_file_number($this->numero_ctps, 7),
            layout_file_number($this->serie_ctps, 5),
            layout_file_date_yyyy($this->data_opcao, 8),
            layout_file_date_yyyy($this->trabalhador->data_nascimento, 8),
            layout_file_number($this->trabalhador->cbo, 5),
            layout_file_number($this->remuneracao_sem_13o, 15),
            layout_file_number($this->remuneracao_13o, 15),
            layout_file_number($this->classe_contribuicao, 2),
            layout_file_number($this->ocorrencia, 2),
            layout_file_number($this->valor_descontado_segurado, 15),
            layout_file_number($this->base_calculo_prevsocial, 15),
            layout_file_number($this->base_calculo_13o_prevsocial_movimento, 15),
            layout_file_number($this->base_calculo_13o_prevsocial_gps, 15),
            layout_file_repeat(' ', 98),
            '*'
        ];
        return implode('', $layout);
    }
}