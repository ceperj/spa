<?php

namespace App\Services\Gfip\Generator;

use App\Models\Person;
use App\Services\Gfip\Layout\GfipType00;
use App\Services\Gfip\Layout\GfipType10;
use App\Services\Gfip\Layout\GfipType30;
use App\Services\Gfip\Layout\GfipType90;
use App\Services\Gfip\Types\Business;
use App\Services\Gfip\Types\EmployeeType13;
use App\Services\Gfip\Types\Responsible;
use Exception;

class GfipGenerator
{
    public function getRowType00(Business $business,
                                 Responsible $responsible,
                                 int $year,
                                 int $month,
                                 int $codigo_recolhimento) : GfipType00
    {
        $this->mustBeBetween($year, 1000, 9999, "O ano informado ($year) não é válido.");
        $this->mustBeBetween($month, 1, 12, "O mês informado ($month) deve estar entre 1 e 12.");
        $this->mustBeBetween($codigo_recolhimento, 0, 999, "O código de recolhimento informado ($codigo_recolhimento) deve conter 3 dígitos.");

        $row = new GfipType00();
        $row->responsavel = $responsible;
        $row->competencia = ['y'=>$year, 'm'=>$month];
        $row->codigo_recolhimento = $codigo_recolhimento;
        $row->inscricao_fornecedor = $business->cnpj;
        return $row;
    }

    public function getRowType10(Business $business,
                                 int $codigo_centralizacao,
                                 int $fpas) : GfipType10
    {
        $this->mustBeBetween($codigo_centralizacao, 0, 9, "O código de centralização informado ($codigo_centralizacao) possui mais de um dígito.");
        $this->mustBeBetween($fpas, 0, 999, "O FPAS informado ($fpas) deve conter 3 dígitos.");

        $row = new GfipType10();
        $row->empresa = $business;
        $row->codigo_centralizacao = $codigo_centralizacao;
        $row->fpas = $fpas;
        $row->banco = $business->banco;
        return $row;
    }

    public function getRowType30(Business $business,
                                 Person $model) : GfipType30
    {
        $row = new GfipType30();
        $row->empresa = $business;
        $row->trabalhador = EmployeeType13::fromModel($model);
        $row->remuneracao_sem_13o = 'CALCULAR';
        $row->remuneracao_13o = 'CALCULAR';
        $row->valor_descontado_segurado = 'CALCULAR';
        $row->base_calculo_prevsocial = 'CALCULAR';
        $row->base_calculo_13o_prevsocial_movimento = 'CALCULAR';
        $row->base_calculo_13o_prevsocial_gps = 'CALCULAR';
        return $row;
    }

    public function getRowType90()
    {
        return new GfipType90();
    }

    public function getResponsible($json) : Responsible
    {
        if (is_string($json))
            $json = json_decode($json);

        return new Responsible($json);
    }

    public function getBusiness($json) : Business
    {
        if (is_string($json))
            $json = json_decode($json);

        return new Business($json);
    }

    private function mustBeBetween(int $value, int $min, $max, $message)
    {
        if ($value < $min || $value > $max)
            throw new Exception($message);
    }
}