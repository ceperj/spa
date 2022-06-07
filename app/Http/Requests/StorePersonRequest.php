<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\Bank;
use App\Models\Battery;
use App\Models\Job;
use App\Models\Project;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

class StorePersonRequest extends StandardFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|between:2,192',
            'cpf' => 'required|string|size:11',
            'rg' => 'required|string|between:1,192',
            'rgexp' => 'required|string',
            'pis' => 'required|string',
            'phone1' => 'sometimes',
            'phone2' => 'sometimes',
            'project_id' => ['required', $this->existsAndActive(Project::class)],
            'bank_id' => ['required', $this->existsAndActive(Bank::class)],
            'bank_agency' => 'required|string',
            'bank_account' => 'required',
            'battery_id' => ['required', $this->existsAndActive(Battery::class)],
            'email' => 'required|email',
            'job_id' => ['required', $this->existsAndActive(Job::class)],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome Completo',
            'phone1' => 'Telefone',
            'phone2' => 'Celular',
            'project_id' => 'Projeto',
            'bank_id' => 'Banco',
            'battery_id' => 'Bateria de Pagamento',
            'bank_agency' => 'Agência',
            'bank_account' => 'Conta (com dígito)',
            'job_id' => 'Cargo ou Profissão',
        ];
    }

    function existsAndActive($model)
    {
        // Rule "exists" does not take into account the Hashids:
        //   Rule::exists($modelOrTable, 'id')->where($this->getActiveFn('status'));
        //
        // Then we create a custom closure which decodes the id ($value)
        // and fails if that model ($model) does not exist.
        // @see https://stackoverflow.com/a/51167204/2084091
        return function($attribute, $value, $fail) use ($model)
        {
            if (! $value)
                return $fail('O campo é obrigatório.');

            $idArray = Hashids::connection($model)->decode($value);
            if (count($idArray) !== 1)
                return $fail("Há algo errado com o registro informado.");
            
            $id = $idArray[0];
            $result = $model::onlyActive()->where('id', $id)->first();
            if (! $result)
                return $fail("Não foi possível localizar o registro no banco de dados.");
        };
    }

    function getActiveFn($statusField)
    {
        return fn ($query) => $query->where($statusField, Constants::STATUS_ACTIVE);
    }
}
