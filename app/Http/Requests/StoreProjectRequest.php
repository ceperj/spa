<?php

namespace App\Http\Requests;

class StoreProjectRequest extends StandardFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'projectName' => 'required|string|between:3,192',
            'sector' => 'required|string|max:192',
            'process' => 'present|string|nullable|max:192',
            'projectManager' => 'present|string|nullable|max:192',
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required |date_format:Y-m-d|after:startDate',
        ];
    }

    public function attributes()
    {
        return [
            'process' => 'Processo-mãe',
            'projectName' => 'Nome do Projeto',
            'projectManager' => 'Gerente do Projeto',
        ];
    }
}
