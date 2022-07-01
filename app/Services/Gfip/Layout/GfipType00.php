<?php

namespace App\Services\Gfip\Layout;

use App\Services\Gfip\Types\Responsible;
use App\Services\Layouts\ILayoutRow;

/**
 * Represents a layout header (type 00), the responsible for the lot.
 */
class GfipType00 implements ILayoutRow
{
    public $tipo_remessa = '1'; // 1-GFIP, 2-DERF
    public Responsible $responsavel;
    public $competencia;
    public $codigo_recolhimento;
    public $indicador_recolhimento_fgts = '1';
    public $modalidade_arquivo = '1';
    public $data_recolhimento_fgts = null;
    public $indicador_recolhimento_prevsocial = '1';
    public $data_recolhimento_prevsocial = null;
    public $indice_recolhimento_atraso = null;
    public $tipo_incricao_fornecedor = '1';
    public $inscricao_fornecedor;

    public function toLayoutRow(): string
    {
        $layout = [
            layout_file_number('00', 2),
            layout_file_repeat(' ', 51),
            layout_file_number($this->tipo_remessa, 1),
            layout_file_number($this->responsavel->type, 1),
            layout_file_number($this->responsavel->cnpj, 14),
            layout_file_alpha($this->responsavel->razao_social, 30),
            layout_file_alpha($this->responsavel->contato->nome, 20),
            layout_file_alpha($this->responsavel->contato->endereco, 50),
            layout_file_alpha($this->responsavel->contato->bairro, 20),
            layout_file_number($this->responsavel->contato->cep, 8),
            layout_file_alpha($this->responsavel->contato->cidade, 20),
            layout_file_alpha($this->responsavel->contato->uf, 2),
            layout_file_alpha($this->responsavel->contato->telefone, 12),
            layout_file_alpha($this->responsavel->contato->email, 60),
            layout_file_date_yyyy($this->competencia, 6),
            layout_file_number($this->codigo_recolhimento, 3),
            layout_file_number($this->indicador_recolhimento_fgts, 1),
            layout_file_number($this->modalidade_arquivo, 1),
            layout_file_date_yyyy($this->data_recolhimento_fgts, 8),
            layout_file_number($this->indicador_recolhimento_prevsocial, 1),
            layout_file_date_yyyy($this->data_recolhimento_prevsocial, 8),
            layout_file_number($this->indice_recolhimento_atraso, 7),
            layout_file_number($this->tipo_incricao_fornecedor, 1),
            layout_file_number($this->inscricao_fornecedor, 14),
            layout_file_repeat(' ', 18),
            '*'
        ];
        return implode('', $layout);
    }
}
