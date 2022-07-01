<?php

namespace App\Services\Gfip\Types;

use App\Models\Person;

/**
 * Represents an employee type 13.
 */
class EmployeeType13
{
    public $pis_pasep_ci;
    public $data_admissao;
    public $categoria_trabalhador = '13';
    public $nome_trabalhador;
    public $matricula_empregado;
    public $data_nascimento;
    public $cbo;

    static function fromModel(Person $model)
    {
        $instance = new EmployeeType13();
        $instance->pis_pasep_ci = $model->pis;
        $instance->data_admissao = $model->admission_date;
        $instance->nome_trabalhador = $model->name;
        $instance->matricula_empregado = $model->registration_number;
        $instance->data_nascimento = $model->birth_date;
        $instance->cbo = '???????????';
        return $instance;
    }
}
